<?php

namespace frontend\controllers;

use Yii;
use common\models\Order;
use common\models\OrderItem;
use common\models\Product;
use yz\shoppingcart\ShoppingCart;

class CartController extends \yii\web\Controller
{
    public function actionAdd($id)
    {
        $product = Product::findOne($id);
        if ($product) {
            \Yii::$app->cart->put($product);
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
