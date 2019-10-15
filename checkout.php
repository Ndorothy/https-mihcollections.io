<?php
require('autoload.php');
global $lumise;

$data = $lumise->connector->get_session('lumise_cart');
$items = isset($data['items']) ? $data['items'] : null;
$fields = array(
    array('email', 'Billing E-Mail'),
    array('address', 'Street Address'),
    array('zip', 'Zip Code'),
    array('city', 'City'),
    array('country', 'Country')
);

$page_title = $lumise->lang('Checkout');
include(theme('header.php'));
?>
        <div class="lumise-bread">
            <div class="container">
                <h1><?php echo $lumise->lang('Checkout'); ?></h1>
            </div>
        </div>
        <form action="<?php echo $lumise->cfg->url;?>process_checkout.php" method="post" class="form-horizontal" id="checkoutform" accept-charset="utf-8">
        <div class="container">
            <div class="row">
            	<div id="checkout" class="padding6 span12">
                    <?php if(count($items) > 0):?>
                        <div class="col-md-6 billing">
                            <h3><?php echo $lumise->lang('Billing Information'); ?></h3>
                            <div class="control-group span6">
                				<label for="first_name" class="control-label"><?php echo $lumise->lang('First Name'); ?><em>*</em></label>
                				<div class="controls">
                					<input name="first_name" type="text" value="" placeholder="Katie" id="first_name" required>
                				</div>
                			</div>
                            <div class="control-group span6 last">
                				<label for="last_name" class="control-label"><?php echo $lumise->lang('Last Name'); ?><em>*</em></label>
                				<div class="controls">
                					<input name="last_name" type="text" placeholder="King" value="" id="last_name" required>
                				</div>
                			</div>
                            <div class="control-group">
                				<label for="email" class="control-label"><?php echo $lumise->lang('Billing E-Mail'); ?><em>*</em></label>
                				<div class="controls">
                					<input name="email" type="email" value="" id="email" required>
                				</div>
                			</div>
                			<div class="control-group">
                				<label for="address" class="control-label"><?php echo $lumise->lang('Street Address'); ?><em>*</em></label>
                				<div class="controls">
                                    <input name="address" placeholder="229 Broadway" type="text" value="" id="address" required>
                				</div>
                			</div>
                			<div class="control-group span6">
                				<label for="zip" class="control-label"><?php echo $lumise->lang('Zip Code'); ?><em>*</em></label>
                				<div class="controls">
                                    <input name="zip" type="text" value="" id="zip" required>
                				</div>
                			</div>
                			<div class="control-group span6 last">
                				<label for="city" class="control-label"><?php echo $lumise->lang('City'); ?><em>*</em></label>
                				<div class="controls">
                                    <input name="city" type="text" placeholder="New York" value="" id="city" required>
                				</div>
                			</div>
                			<div class="control-group span6">
                				<label for="country" class="control-label"><?php echo $lumise->lang('Country'); ?><em>*</em></label>
                				<div class="controls">
                					<select name="country" id="country" required>
                						<option value=""><?php echo $lumise->lang('Country'); ?></option>
      
                	                     <option value="UG">Uganda</option>
										 <option value="KE">Kenya</option>
										 <option value="TZ">Tanzania</option>
										 <option value="RW">Rwanda</option>
                						
                					</select>
                				</div>
                			</div>
                            <div class="control-group span6 last">
                				<label for="phone" class="control-label"><?php echo $lumise->lang('Phone'); ?><em>*</em></label>
                				<div class="controls">
                                    <input name="phone" type="text" value="" id="phone" required>
                				</div>
                			</div>
                            <div class="control-group last payments">
                                <h3>Payment</h3>
                				<div class="controls">
                                    <div class="lumise-payment-item">
                                        <input name="payment" type="radio" value="cod" id="payment-cod" required>
                                        <label for="payment-cod"><?php echo $lumise->lang('Cash on delivery'); ?></label>
                                    </div>
                                    <div class="lumise-payment-item">
                                        <input name="payment" type="radio" value="paypal" id="payment-paypal" required>
                                        <label for="payment-paypal"><img src="<?php echo $lumise->cfg->url.'assets/images/paypal.png'; ?>" alt="<?php echo $lumise->lang('Paypal payment'); ?>"/><?php echo $lumise->lang('Paypal'); ?></label>
                                    </div>
                                    <label for="payment" class="error"></label>
                				</div>
                			</div>
                        </div>
                        <div class="col-md-6 order_overview">
                            <h3>Order Review</h3>
                            <div class="wrap-table">
                                <table class="lumise-table sty2">
                                    <thead>
                                        <tr>
                                            <th><?php echo $lumise->lang('Product Name'); ?></th>
                                            <th><?php echo $lumise->lang('Thumbnails'); ?></th>
                                            <th><?php echo $lumise->lang('Attributes'); ?></th>
                                            <th><?php echo $lumise->lang('Qty'); ?></th>
                                            <th class="text-right"><?php echo $lumise->lang('Subtotal'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $total = 0;
                                        foreach($items as $item):
                                            $cart_data = $lumise->lib->get_cart_item_file($item['file']);
                                            $item = array_merge($item, $cart_data);
                                            ?>
                                        <tr>
                                            <td><?php echo $item['product_name'];?></td>
                                            <td>
                                                <?php

                                                if(count($item['screenshots'])> 0):
                                                    foreach($item['screenshots'] as $image):?>
                                                        <img width="150" src="<?php echo $image;?>" />
                                                    <?php endforeach;
                                                endif;
                                                ?>
                                            </td>
                                            <td>
	                                            
	                                            <?php foreach($item['attributes'] as $attr => $options) { ?>
                                                <p>
                                                    <strong><?php echo $options['name']; ?></strong> : 
                                                    <?php
	                                                    
														$cols = explode("\n", isset($options['values']) ? $options['values'] : '');
														$val = trim($options['value']);
														$lab = $val;
														
	                                                    if ($options['type'] == 'color' || $options['type'] == 'product_color') {
														foreach ($cols as $col) {
															$col = explode('|', $col);
															$col[0] = trim($col[0]);
															if ($col[0] == $val && isset($col[1]) && !empty($col[1]))
																$lab = $col[1];
														}
														echo '<span title="'.htmlentities($val).'" style="background:'.$val.';padding: 3px 8px;border-radius: 12px;">'.htmlentities($lab).'</span>';
													} else echo '<span>'.$val.'</span>';
													
                                                    ?>
                                                </p>
												<?php }?>
												
                                            </td>
                                            <td><?php echo $item['qty'];?></td>
                                            <td class="text-right"><?php echo $lumise->lib->price($item['price']['total']);?><?php $total += $item['price']['total'];?></td>
                                        </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" class="text-right"><strong><?php echo $lumise->lang('Sub Total'); ?></strong></td>
                                            <td class="text-right"><?php echo $lumise->lib->price($total);?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-right"><strong><?php echo $lumise->lang('Grand Total'); ?></strong></td>
                                            <td class="text-right"><?php $grand_total = $total;?><?php echo $lumise->lib->price($grand_total);?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="control-group span12 comment">
                				<label for="comment" class="control-label"><?php echo $lumise->lang('Comments'); ?></label>
                				<div class="controls">
                                    <textarea name="comment" type="text" value="" id="comment"></textarea>
                				</div>
                			</div>
                            <input type="hidden" name="action" value="placeorder">
                            <div class="form-actions">
                				<button name="submit" type="submit" class="btn btn-large btn-primary"><?php echo $lumise->lang('Place Order'); ?></button>
                			</div>
                        </div>
                        
                    <?php else:?>
                        <div class="span12">
                            <p><?php echo $lumise->lang('Your cart is currently empty.'); ?></p>
                        </div>
                        <div class="form-actions">
                            <a href="<?php echo $lumise->cfg->url;?>" class="btn btn-large btn-primary"><?php echo $lumise->lang('Continue Shopping'); ?></a>
                        </div>
                    <?php endif;?>
            	</div>
            </div>
        </div>
        </form>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            $("#checkoutform").validate();
        });
        </script>
<?php
include(theme('footer.php'));
//update cart info

$data['total'] = $grand_total;
$lumise->connector->set_session('lumise_cart', $data);
