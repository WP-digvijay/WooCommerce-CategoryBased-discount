<?php
    /**
     * Plugin Name: WooCommerce Addon Discount
     * Description: A plugin to add a discount on WooCommerce products.
     * Version: 1.0.0
     * Author: digvijay
     * Author URI: https://wp-digvijay.github.io/Portfolio/
     */
    if(!defined('ABSPATH')){
        exit; 
    }
    if(!class_exists('Rd_Woo_Accessories_Discount')){
        class Rd_Woo_Accessories_Discount{
            private $discount_amount = 0;
            private $discount_lable = 'Accessorices Discount';

            public function __construct(){
                add_action('woocommerce_cart_calculate_fees', [$this, 'apply_discount']);
                add_action('woocommerce_review_order_before_cart_contents', [$this, 'display_discount_label']);
            }
            //this one work when one accessories product in cart total cart value above or equal to 2000
            // public function apply_discount($cart){
            //     if(is_admin() && !defined('DOING_AJAX')){
            //         return;
            //     }
            //     $accessories = false;
            //     $cart_total  = $cart->get_subtotal();
                
            //     foreach($cart->get_cart() as $cart_item){
            //         $product_id = $cart_item['product_id'];
            //         $terms = get_the_terms($product_id, 'product_cat', true);

            //         if($terms && !is_wp_error($terms)){
            //             foreach($terms as $term){
            //                 if(strtolower($term->name) === 'accessories'){
            //                     $accessories = true;
            //                     break 2;
            //                 }
            //             }
            //         }
            //     }

            //     if($accessories && $cart_total >= 2000){
            //         $discount = $cart_total * 0.15;
            //         $cart->add_fee($this->discount_lable, -$discount, false);
            //         WC()->session->set('accessories_discount_applied', true);
            //     }
            //     else{
            //         WC()->session->__unset('accessories_discount_applied');
            //     }
            // }

            //this one work when accessories product in cart total accessories product value ablove or equal to 2000
            public function apply_discount($cart){
                if(is_admin() && !defined('DOING_AJAX')){
                    return;
                }
                $accessories_subtotal = 0;
                $has_accessories = false;

                foreach($cart->get_cart() as $cart_item){
                    $product_id = $cart_item['product_id'];
                    $terms = get_the_terms($product_id, 'product_cat', true);

                    if($terms && !is_wp_error($terms)){
                        foreach($terms as $term){
                            if(strtolower($term->name) === 'accessories'){
                                $has_accessories = true;
                                $accessories_subtotal += $cart_item['line_subtotal'];
                                break;
                            }
                        }
                    }
                }

                if($has_accessories && $accessories_subtotal >= 2000){
                    $discount = $accessories_subtotal * 0.15;
                    $cart->add_fee($this->discount_lable, -$discount, false);
                    WC()->session->set('accessories_discount_applied', true);
                } else {
                    WC()->session->__unset('accessories_discount_applied');
                }
            }
            public function display_discount_label(){
                if(WC()->session->get('accessories_discount_applied')){
                    wc_print_notice('You’ve received a 15% Accessories Discount!', 'success');
                }
            }
        }
        new Rd_Woo_Accessories_Discount();
    }
    
