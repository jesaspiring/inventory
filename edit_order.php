<?php
/**
 * edit_order.php
 *
 * @package default
 */


$page_title = 'Edit category';
require_once 'includes/load.php';
// Checkin What level user has permission to view this page
page_require_level(2);


//Display all catgories.
$order = find_by_id('orders', (int)$_GET['id']);
if (!$order) {
	$session->msg("d", "Missing order id.");
	redirect('orders.php');
}

if (isset($_POST['edit_order'])) {
	$customer = remove_junk($db->escape($_POST['customer']));
	$paymethod = remove_junk($db->escape($_POST['paymethod']));
	$notes = remove_junk($db->escape($_POST['notes']));
	$date = remove_junk($db->escape($_POST['date']));
	if ($date == 0 ) { $date    = make_date(); }

	if (empty($errors)) {
		$sql = "UPDATE orders SET";
		$sql .= " customer='{$customer}', paymethod='{$paymethod}', notes='{$notes}', date='{$date}'";
		$sql .= " WHERE id='{$order['id']}'";

		$result = $db->query($sql);
		if ($result && $db->affected_rows() === 1) {
			$session->msg("s", "Successfully updated order");
			redirect('orders.php', false);
		} else {
			$session->msg("d", "Sorry! Failed to Order");
			redirect('orders.php', false);
		}
	} else {
		$session->msg("d", $errors);
		redirect('orders.php', false);
	}
}
?>
<?php include_once 'layouts/header.php'; ?>

<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
   </div>
   <div class="col-md-5">
     <div class="panel panel-default">
       <div class="panel-heading">
         <strong>
           <span class="glyphicon glyphicon-th"></span>
           <span>Editing Order #<?php echo remove_junk(ucfirst($order['id']));?></span>
        </strong>
       </div>
       <div class="panel-body">
         <form method="post" action="edit_order.php?id=<?php echo (int)$order['id'];?>">
           <div class="form-group">
               <input type="text" class="form-control" name="customer" value="<?php echo remove_junk(ucfirst($order['customer']));?>">
           </div>

           <div class="form-group">
                    <select class="form-control" name="paymethod">
                      <option value="">Select Payment Method</option>
                     <option value="Cash" <?php if ($order['paymethod'] === "Cash" ): echo "selected"; endif; ?> >Cash</option>
                     <option value="Check" <?php if ($order['paymethod'] === "Check" ): echo "selected"; endif; ?> >Check</option>
                     <option value="Credit" <?php if ($order['paymethod'] === "Credit" ): echo "selected"; endif; ?> >Credit</option>
                     <option value="Charge" <?php if ($order['paymethod'] === "Charge" ): echo "selected"; endif; ?> >Charge to Account</option>
                    </select>

           </div>

           <div class="form-group">
               <input type="text" class="form-control" name="notes" value="<?php echo remove_junk(ucfirst($order['notes']));?>" placeholder="Notes">
           </div>

           <div class="form-group">
           <input type="date" class="form-control datepicker" name="date" data-date-format="" value="<?php echo remove_junk($order['date']); ?>">
           </div>

           <button type="submit" name="edit_order" class="btn btn-primary">Update order</button>
       </form>
       </div>
     </div>


<?php
// print "<pre>";
// print_r($order);
// print "</pre>\n";
?>

   </div>
</div>

<?php include_once 'layouts/footer.php'; ?>
