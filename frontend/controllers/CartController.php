<?php

namespace frontend\controllers;
use Yii;
use common\models\Order;
use common\models\OrderItem;
use common\models\Product;
use yz\shoppingcart\ShoppingCart;
use pjhl\multilanguage\LangHelper;
class CartController extends \yii\web\Controller
{
    public function actionAdd($id)
    {	
        $product = Product::findOne($id);
		$pr_id = $product['product_id'];
		 $session = Yii::$app->session;
		//$product['add'] = $session['quantity_'.$id.''];
		//print_r($product);die;
		if($session['quantity_'.$pr_id.'']):
		$add = $session['quantity_'.$pr_id.''] - 1;
        unset($session['quantity_'.$pr_id.'']);
		else:
		$add = 0;
		endif;
		if ($product) {
            \Yii::$app->cart->put($product,$add);
            return $this->goBack();
        }
    }
    public function actionPay()
	{
		$session = Yii::$app->session;
		$amount = $session->get('total');
		echo $amount;
		return $this->render('pay',compact('amount'));
	}
    public function actionList()
    {
        /* @var $cart ShoppingCart */
        $cart = \Yii::$app->cart;
        $products = $cart->getPositions();
        $total = $cart->getCost();
        //print_r($products);
        return $this->render('list', [
           'products' => $products,
           'total' => $total,
        ]);
    }
    public function actionRemove($id)
    {
        $product = Product::find()->where(['product_id'=>$id])->all();
		//echo '<pre>'.print_r($product,true).'</pre>';
        if ($product) {
            \Yii::$app->cart->remove($product[0]);
            \Yii::$app->cart->remove($product[1]);
			\Yii::$app->cart->remove($product[2]);
			$this->redirect(['cart/list']);
        }
    }
    public function actionUpdate($id, $quantity)
    {
        $product = Product::find()->where(['product_id'=>$id])->all();
        if ($product) {
            \Yii::$app->cart->update($product[0], $quantity);
            \Yii::$app->cart->update($product[1], $quantity);
			\Yii::$app->cart->update($product[2], $quantity);
			$this->redirect(['cart/list']);
        }
    }
	 public function actionUpdate1($id, $quantity)
	{//echo $id;die;
		$product = Product::find()->where(['product_id'=>$id])->all();
    	if ($product) {
//Використай id для формування массиву сесій;
			$session = Yii::$app->session;
			$session['quantity_'.$id.''] = $quantity;
            //print_r($session['quantity_'.$id.'']);//die;
			$quantities = $session['quantity_'.$id.''];
			//echo $session['quantity_1'];die;
			//unset($session['quantity']);
			//echo $quantities;die;
            //\Yii::$app->cart->update($product[0], $quantity);
			//\Yii::$app->cart->update($product[1], $quantity);
			//\Yii::$app->cart->update($product[2], $quantity);	
			if(LangHelper::getLanguage('id') == 1):
			$this->redirect(['catalog/view','id'=>Yii::$app->request->get('id'),'name'=>$product[1]['slug'],'quantity'=>$quantities]);
			elseif(LangHelper::getLanguage('id') == 3):
			$this->redirect(['catalog/view','id'=>Yii::$app->request->get('id'),'name'=>$product[2]['slug'],'quantity'=>$quantities]);
			else:
			$this->redirect(['catalog/view','id'=>Yii::$app->request->get('id'),'name'=>$product[0]['slug'],'quantity'=>$quantities]);
        endif;
		}
	} 
    public function actionOrder()
    {
		$session = Yii::$app->session;
		//$language = $session->set('language');
        $order = new Order();
        /* @var $cart ShoppingCart */
        $cart = \Yii::$app->cart;
        /* @var $products Product[] */
        $products = $cart->getPositions();
        $total = $cart->getCost();
        $session->set('total',$total);
        if ($order->load(\Yii::$app->request->post()) && $order->validate()) {
            $transaction = $order->getDb()->beginTransaction();
            $order->save(false);
           
            foreach($products as $product) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->title = $product->title;
                $orderItem->price = $product->getPrice();
                $orderItem->product_id = $product->id;
                $orderItem->quantity = $product->getQuantity();               
			   if (!$orderItem->save(false)) {
                    $transaction->rollBack();
                    \Yii::$app->session->addFlash('error', 'Cannot place your order. Please contact us.');
                    return $this->redirect('catalog/list');
                }
            }
            $transaction->commit();
            \Yii::$app->cart->removeAll();
            \Yii::$app->session->addFlash('success', 'Thanks for your order. We\'ll contact you soon.');
            $order->sendEmail();
            return $this->redirect('/catalog/list');
        }
        return $this->render('order', [
            'order' => $order,
            'products' => $products,
            'total' => $total,
        ]);
    }
}