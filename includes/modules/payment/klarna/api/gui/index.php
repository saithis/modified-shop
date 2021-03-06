<?php
include("klarna_settings.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Klarna API Test</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="Cache-Control" content="No-Cache">
<link rel="Stylesheet" href="css/style.css" />

<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/populate_data.js"></script>
<script type="text/javascript" src="js/listener.js"></script>

  <script>
  $(document).ready(function() {
    $("#tabs").tabs();
    $("#tabs-1").tabs();
    $("#tabs-2").tabs();
    $("#tabs-3").tabs();
    $("#tabs-4").tabs();
  });
  </script>


</head>
<body>

<div id="container">
 <div id="inner-container">
 
  <div id="header">
   <div id="logotyp"> </div>
   <h1>Klarna API Test</h1>
   <p>Here you can test the XML-RPC and PHP API with Klarna.</p>
   <form id="estore-info" onsubmit="return false;">
   <p>Connecting to: <select name="host">
   <?php
   foreach($KLARNA_HOSTS as $hostname => $host)
   {
   	if($host['host'] == $KREDITOR_HOST && $host['port'] == $KREDITOR_PORT)
   		$selected = ' selected="selected"';
   	else
   		$selected = '';
   		
   	echo"<option value=\"$hostname\"$selected>{$host['host']}:{$host['mode']}</option>";
   }
   ?>
   </select></p>
   EID: <input name="eid" type="text" value="<?php echo $eid; ?>" style="width: 50px; margin-right: 20px;" /> Shared secret: <input type="text" name="secret" value="<?php echo $secret; ?>" />
   <input type="submit" value="Save" />
   </form>
   <p />
  </div>
 
  <div id="content">
  
  <div id="result-dialog"></div>

<div id="tabs">
    <ul>
        <li><a href="#fragment-1"><span>Standard integration</span></a></li>
        <li><a href="#fragment-2"><span>Useful functions</span></a></li>
        <li><a href="#fragment-3"><span>Special functions</span></a></li>
        <li><a href="#fragment-4"><span>Reservation functions</span></a></li>        
    </ul>
    <div id="fragment-1">
	<h2>Standard integration</h2>

	<div id="tabs-1">
	 <ul>
	  <li><a href="#add-transaction"><span>Add transaction</span></a></li>
	  <li><a href="#get-address"><span>Get address</span></a></li>
	  <li><a href="#activate-invoice"><span>Activate invoice</span></a></li>
	  <li><a href="#delete-invoice"><span>Delete invoice</span></a></li>	  
	 </ul>
	 
	 <div id="add-transaction">
	 
	 	<h3>Add Transaction</h3>
	 	<b>Choose a test person or company:</b>
		<p />
		<table>
		<tr>
		 <td><b>Country</b></td>
		 <td><b>Approved</b></td>
		 <td><b>Not approved</b></td>
		</tr>
		<tr>
		 <td width="120">
		  <strong>Sweden</strong>
		 </td>
		 <td>
		  <input type="button" value="Karl Lidin" onclick="setKarl();">
		  <input type="button" value="Kalle Anka AB" onclick="setKalleAnka();">
		 </td>
		 <td>
		  <input type="button" value="Maud Johansson" onclick="setMaud();">
		  <input type="button" value="Bj&ouml;rnligan AB" onclick="setBjornligan();">
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Finland</strong>
		 </td>
		 <td>
		  <input type="button" value="Suvi Aurinkoinen" onclick="setSuvi();">
		  <input type="button" value="Porin Mies-Laulu r.y." onclick="setPorin();">
		 </td>
		 <td>
		  <input type="button" value="Mikael Miehinen" onclick="setMikael();">
		  <input type="button" value="Mankalan Perhekodit Oy" onclick="setMankalan();">
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Denmark</strong>
		 </td>
		 <td>
		  <input type="button" value="Rasmus Jens-Peter Lybert" onclick="setRasmus();">
		  <input type="button" value="Onbase ApS" onclick="setOnbase();">
		 </td>
		 <td>
		  <input type="button" value="Larsen & Olsen Contracters Aps" onclick="setLarsenOlsen();">
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Norway</strong>
		 </td>
		 <td>
		  <input type="button" value="Petter Testmann" onclick="setPetter();">
		 </td>
		 <td>
		  <input type="button" value="Petra Testdame" onclick="setPetra();">
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Germany</strong>
		 </td>
		 <td>
		  <input type="button" value="Uno Eins" onclick="setUnoEins();">
		 </td>
		 <td>
		  <input type="button" value="Uno Vier" onclick="setUnoVier();">
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Netherlands</strong>
		 </td>
		 <td>
		  <input type="button" value="Test Persoon" onclick="setTest();">
		 </td>
		</tr>
		</table>

		<p />
		<form onsubmit="return false;" name="add_invoice_form" id="add-invoice-form">
		<table>
		<tr>
		<td>
		 <table>
		 <tr>
		  <td width="180">
		   <strong>SSN/Birth date:</strong>
		  </td>
		  <td>
		   <input type="text" name="pno" size="10" value="4304158399"> (*)
		  </td>
		 </tr>
		 <tr>
		  <td>
		   <strong>First name:</strong>
		  </td>
		  <td>
		   <input type="text" name="fname" size="20" value="Karl"> (*)
		  </td>
		 </tr>
		 <tr>
		  <td>
		   <strong>Last name:</strong>
		  </td>
		  <td>
		   <input type="text" name="lname" size="20" value="Lidin"> (*)
		  </td>
		 </tr>
		 <tr>
		  <td>
		   <strong>Street address:</strong>
		  </td>
		  <td>
		   <input type="text" name="street" size="20" value="Junibacksgatan 42"> (*)
		  </td>
		 </tr>
		 <tr>
		  <td>
		   <strong>House number:</strong>
		  </td>
		  <td>
		   <input type="text" name="housenum" size="20" value=""><br />(* Germany & Netherlands only)
		  </td>
		 </tr>
		 <tr>
		  <td>
		   <strong>House extension:</strong>
		  </td>
		  <td>
		   <input type="text" name="houseext" size="20" value=""><br />(* Netherlands only)
		  </td>
		 </tr>
		 <tr>
		  <td>
		   <strong>Zip code:</strong>
		  </td>
		  <td>
		   <input type="text" name="postno" size="5" value="23634"> (*)
		  </td>
		 </tr>
		 <tr>
		  <td>
		   <strong>City:</strong>
		  </td>
		  <td>
		   <input type="text" name="city" size="20" value="Hollviken"> (*)
		  </td>
		 </tr>
		 <tr>
		  <td>
		   <strong>Country:</strong>
		  </td>
		  <td>
		<select name="country">
		 <option value="sweden">Sweden</option>
		 <option value="norway">Norway</option>
		 <option value="finland">Finland</option>
		 <option value="denmark">Denmark</option>
		 <option value="germany">Germany</option>
		 <option value="netherlands">Netherlands</option>
		</select>
		  </td>
		 </tr>
		 <tr>
		  <td>
		   <strong>E-mail:</strong>
		  </td>
		  <td>
		   <input type="text" name="email" size="20"> (*)
		  </td>
		 </tr>
		 <tr>
		  <td>
		   <strong>Telephone number:</strong>
		  </td>
		  <td>
		   <input type="text" name="telno" size="10"> (*)
		  </td>
		 </tr>
		</table>
		</td>
		<td width="10">
		</td>
		<td valign="top">
		<table>
		<tr>
		 <td qidth="160">
		  <strong>Mobile number:</strong>
		 </td>
		 <td>
		  <input type="text" name="cellno" size="10"> (*)
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Store user:</strong>
		 </td>
		 <td>
		  <input type="text" name="estore_user" size="10">
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Order number:</strong>
		 </td>
		 <td>
		  <input type="text" name="estore_order_no" size="10">
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Auto activation:</strong>
		 </td>
		 <td>
		  <input type="radio" name="auto" value="yes"> Yes
		  <input type="radio" name="auto" value="no" checked> No
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Pre pay:</strong>
		 </td>
		 <td>
		  <input type="radio" name="pre" value="yes"> Yes
		  <input type="radio" name="pre" value="no" checked> No
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Test flag:</strong>
		 </td>
		 <td>
		  <input type="radio" name="test" value="yes"> Yes
		  <input type="radio" name="test" value="no" checked> No
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Currency:</strong>
		 </td>
		 <td>
		<select name="currency">
		 <option value="sek">Swedish kronor</option>
		 <option value="nok">Norwegian kronor</option>
		 <option value="eur">Euro</option>
		 <option value="dkk">Danish kronor</option>
		</select>
		  </td>
		 </tr>
		 <tr>
		  <td>
		   <strong>Language:</strong>
		  </td>
		  <td>
		<select name="language">
		 <option value="swedish">Swedish</option>
		 <option value="norwegian1">Norwegian (Bokm&aring;l)</option>
		 <option value="finnish">Finnish</option>
		 <option value="danish">Danish</option>
		 <option value="german">German</option>
		 <option value="netherlands">Dutch</option>
		</select>
		  </td>
		 </tr>
		 <tr>
		  <td>
		   <strong>Yearly salary:</strong>
		  </td>
		  <td>
		   <input type="text" name="ysalary" size="10" value="0"><br />
		   (* Only for danish part payments)
		  </td>
		 </tr>
		 <tr>
		  <td>
		   <strong>Reference: </strong>
		  </td>
		  <td>
		   <input type="text" name="comment" size="10">
		  </td>
		 </tr>
		 </table>
		<td>
		</tr>
		</table>

		<table border="0">
		<tr>
		<th>Quantity</th>
		<th>Artno</th>
		<th>Description</th>
		<th>Price</th>
		<th>Precision</th>
		<th>Type</th>
		<th>Incl VAT</th>
		<th>VAT %</th>
		</tr>
		<tr>
		<td><input size="3" name="qty0" value="1"></td>
		<td><input size="10" name="artno0" value="10002345"></td>
		<td><input size="25" name="desc0" value="Logitech MX 518"></td>
		<td><input size="8" name="price0" value="19"></td>
		<td>
		<select name="precision0">
		 <option value="1" selected="selected">1</option>
		 <option value="10">10</option>
		 <option value="10">100</option>
		 <option value="10">1000</option>
		</select>
		</td>
		<td>
		<select name="goods_type0">
		 <option value="product">Product</option>
		 <option value="shipment">Shipment fee</option>
		 <option value="handling">Handling fee</option>
		</select>
		</td>
		<td>
		 <input type="checkbox" name="inclvat0" checked="checked" />
		</td>
		<td>
		 <input type="text" name="vat0" size="3" value="25" />
		</td>
		</tr>
		<tr>
		<td><input size="3" name="qty1" value="1"></td>
		<td><input size="10" name="artno1" value="78642453"></td>
		<td><input size="25" name="desc1" value="Macbook Air"></td>
		<td><input size="8" name="price1" value="17"></td>
		<td>
		<select name="precision1">
		 <option value="1" selected="selected">1</option>
		 <option value="10">10</option>
		 <option value="10">100</option>
		 <option value="10">1000</option>
		</select>
		</td>
		<td>
		<select name="goods_type1">
		 <option value="product">Product</option>
		 <option value="shipment">Shipment fee</option>
		 <option value="handling">Handling fee</option>
		</select>
		</td>
		<td>
		 <input type="checkbox" name="inclvat1" checked="checked" />
		</td>
		<td>
		 <input type="text" name="vat1" size="3" value="25" />
		</td>
		</tr>
		<tr>
		<td><input size="3" name="qty2" value="1"></td>
		<td><input size="10" name="artno2" value="77745691"></td>
		<td><input size="25" name="desc2" value="Sandisk Memory stick 32GB"></td>
		<td><input size="8" name="price2" value="12"></td>
		<td>
		<select name="precision2">
		 <option value="1" selected="selected">1</option>
		 <option value="10">10</option>
		 <option value="10">100</option>
		 <option value="10">1000</option>
		</select>
		</td>
		<td>
		<select name="goods_type2">
		 <option value="product">Product</option>
		 <option value="shipment">Shipment fee</option>
		 <option value="handling">Handling fee</option>
		</select>
		</td>
		<td>
		 <input type="checkbox" name="inclvat2" checked="checked" />
		</td>
		<td>
		 <input type="text" name="vat2" size="3" value="25" />
		</td>
		</tr>
		<tr>
		<td><input size="3" name="qty3" value="1"></td>
		<td><input size="10" name="artno3" value="S1"></td>
		<td><input size="25" name="desc3" value="Shipment fee"></td>
		<td><input size="8" name="price3" value="15"></td>
		<td>
		<select name="precision3">
		 <option value="1" selected="selected">1</option>
		 <option value="10">10</option>
		 <option value="10">100</option>
		 <option value="10">1000</option>
		</select>
		</td>
		<td>
		<select name="goods_type3">
		 <option value="product">Product</option>
		 <option value="shipment" selected="selected">Shipment fee</option>
		 <option value="handling">Handling fee</option>
		</select>
		</td>
		<td>
		 <input type="checkbox" name="inclvat3" checked="checked" />
		</td>
		<td>
		 <input type="text" name="vat3" size="3" value="25" />
		</td>
		</tr>
		<tr>
		<td><input size="3" name="qty4" value="1"></td>
		<td><input size="10" name="artno4" value="H1"></td>
		<td><input size="25" name="desc4" value="Handling fee"></td>
		<td><input size="8" name="price4" value="15"></td>
		<td>
		<select name="precision4">
		 <option value="1" selected="selected">1</option>
		 <option value="10">10</option>
		 <option value="10">100</option>
		 <option value="10">1000</option>
		</select>
		</td>
		<td>
		<select name="goods_type4">
		 <option value="product">Product</option>
		 <option value="shipment">Shipment fee</option>
		 <option value="handling" selected="selected">Handling fee</option>
		</select>
		</td>
		<td>
		 <input type="checkbox" name="inclvat4" checked="checked" />
		</td>
		<td>
		 <input type="text" name="vat4" size="3" value="25" />
		</td>
		</tr>
		<tr>
		<td colspan="8" align="right"><input type="submit" value="Create Invoice"></td>
		</tr>
		</table>
		</form>
	</div>
	
		 
	 <div id="get-address">
		<h3>Get addresses</h3>
		<div style="width: 500px;">
		<p>Enter pno to get customers addresses. (Swedish)</p>
		</div>
		<form onsubmit="return false;" id="get-address-form">
		<p><strong>SSN/Birth date:</strong>
		<input type="text" name="pno" size="10" value=""> (*)<br/>
		<input type="submit" value="Get addresses"></p>
		</form>	 
	 </div>
	
	<div id="activate-invoice">
		<h3>Activate Invoice</h3>
		<div style="width: 500px;">
		<p>Enter invoice number of an passive invoice you would like to activate.</p>
		</div>
		<form onsubmit="return false;" id="activate-invoice-form">
		<p><strong>Invoice number:</strong>
		<input type="text" name="invno" size="10" value=""> (*)
		<input type="submit" value="Activate"></p>
		</form>
	</div>
	<div id="delete-invoice">
		<h3>Delete Invoice</h3>
		<div style="width: 500px;">
		<p>Enter invoice number of an passive invoice you would like to delete.</p>
		</div>
		<form onsubmit="return false;" id="delete-invoice-form">
		<p><strong>Invoice number:</strong>
		<input type="text" name="invno" size="10" value=""> (*)
		<input type="submit" value="Delete"></p>
		</form>
		</div>
	 
	</div>


    </div>
    <div id="fragment-2">
	<h2>Useful functions</h2>
	
	<div id="tabs-2">
	 <ul>

	  <li><a href="#return-amount"><span>Return amount</span></a></li>
	  <li><a href="#credit-invoice"><span>Credit invoice</span></a></li>
	  <li><a href="#credit-part"><span>Credit part</span></a></li>
	  <li><a href="#email-invoice"><span>Email invoice</span></a></li>
	  <li><a href="#send-invoice"><span>Send invoice</span></a></li>
	 </ul>
	

	<div id="return-amount">
		<h3>Return Amount</h3>
		<div style="width: 500px;">
		<p>Enter an invoice number and the amount you will return from the invoice.</p>
		</div>
		<form onsubmit="return false;" id="return-amount-form">
		<p><strong>Invoice number:</strong>
		<input type="text" name="invno" size="10" value=""> (*)</p>
		<strong>Amount:</strong>
		<input type="text" name="amount" size="10" value=""> (*)</p>
		<p><strong>Vat:</strong>
		<input type="text" name="vat" size="10" value="25"> (*)</p>
		<p><strong>Flags:</strong>
		<input type="text" name="flags" size="5" value=""></p>
		<p>Note: on "vat" you fill out the correct vat on the amount you want to return or just leave it "0".</p>
		<p><input type="submit" value="Return"></p>
		</form>
	</div>
	<div id="credit-invoice">
		<h3>Credit Invoice</h3>
		<div style="width: 500px;">
		<p>Enter invoice number of an active invoice you would like to return.</p>
		</div>
		<form onsubmit="return false;" id="credit-invoice-form">
		<p><strong>Invoice number:</strong>
		<input type="text" name="invno" size="10" value=""> (*)</p>
		<p><strong>Credit number: </strong>
		<input type="text" name="credno" size="10" value=""></p>
		<p><input type="submit" value="Return"></p>
		</form>
	</div>
	<div id="credit-part">
		<h3>Credit Part</h3>
		<div style="width: 500px;">
		<p>Enter invoice number, quantity and article number of an active invoice you would like to credit.</p>
		</div>
		<form onsubmit="return false;" id="credit-part-form">
		<p><strong>Invoice number:</strong>
		<input type="text" name="invno" size="10" value=""> (*)</p>
		<p><strong>Credit number: </strong>
		<input type="text" name="credno" size="10" value=""></p>
		<p>Note: All Artno fields must contain a valid artno from the invoice, use quantity 0 if you want the item to remain on the invoice.</p> 
		<table>
		<tr>
		<th>Quantity</th>
		<th>Artno</th>
		</tr>
		<tr>
		<td><input size="3" name="qty0" value=""></td>
		<td><input size="10" name="artno0" value=""></td>
		</tr>
		<tr>
		<td><input size="3" name="qty1" value=""></td>
		<td><input size="10" name="artno1" value=""></td>
		</tr>
		<tr>
		<td><input size="3" name="qty2" value=""></td>
		<td><input size="10" name="artno2" value=""></td>
		</tr>
		<tr>
		<td colspan="4" align="right"><input type="submit" value="Return"></td>
		</tr>
		</table>
		</form>
	</div>
	<div id="email-invoice">
		<h3>Email Invoice</h3>
		<div style="width: 500px;">
		<p>Enter invoice number of an active invoice you would like to email.</p>
		</div>
		<form onsubmit="return false;" id="email-invoice-form">
		<p><strong>Invoice number:</strong>
		<input type="text" name="invno" size="10" value=""> (*)
		<input type="submit" value="Send"></p>
		</form>
	</div>
	<div id="send-invoice">
		<h3>Send Invoice</h3>
		<div style="width: 500px;">
		<p>Enter invoice number of an active invoice you would like to send.</p>
		</div>
		<form onsubmit="return false;" id="send-invoice-form">
		<p><strong>Invoice number:</strong>
		<input type="text" name="invno" size="10" value=""> (*)
		<input type="submit" value="Send"></p>
		</form>
	</div>
	</div>
    </div>
    <div id="fragment-3">
	<h2>Special functions</h2>
	
	<div id="tabs-3">
	 <ul>
	  <li><a href="#update-goods-quantity"><span>Update goods quantity</span></a></li>
	  <li><a href="#update-charge-amount"><span>Update charge amount</span></a></li>
	  <li><a href="#update-order-number"><span>Update order number</span></a></li>
	  <li><a href="#invoice-address"><span>Invoice Address</span></a></li>
	  <li><a href="#invoice-amount"><span>Invoice Amount</span></a></li>
	  <li><a href="#invoice-part-amount"><span>Get amount of invoice part</span></a></li>
	  <li><a href="#get-pclasses"><span>Get pclasses</span></a></li>
	  <li><a href="#is-invoice-paid"><span>Is invoice paid</span></a></li>
	 </ul>
	
	 <div id="update-goods-quantity">
		<h3>Update goods quantity</h3>
		<div style="width: 500px">
		<p> Enter invoice number of a passive invoice on which you would like to update the quantity of a goods.</p>
		</div>
		<form onsubmit="return false;" id="update-goods-qty-form">
		<p><strong>Invoice number:</strong>
		<input type="text" name="invno" size="10" value=""> (*)<br>

		<table>
		<tr>
		<th>Quantity</th>
		<th>Artno</th>
		</tr>
		<tr>
		<td><input size="3" name="qty" /></td>
		<td><input size="10" name="artno" /></td>
		</tr>
		</table>
		<input type="submit" value="Update goods quantity">
		</p>
		</form>
	 </div>
	 <div id="update-charge-amount">
		<h3>Update charge amount</h3>
		<div style="width: 500px;">
		<p>Enter invoice number of a passive invoice on which you would like to update the charge amount.</p>
		</div>
		<form onsubmit="return false;" id="update-charge-form">
		<p><strong>Invoice number:</strong>
		<input type="text" name="invno" size="10" value=""> (*)<br>
		<input type="radio" name="type" value="shipment"> Shipment<br>
		<input type="radio" name="type" value="handling" checked> Handling<br>
		<strong>New amount: </strong>
		<input type="text" name="newAmount" size="5" value="">
		<input type="submit" value="Update charge amount">
		</p>
		</form>
	 </div>
	 <div id="update-order-number">
		<h3>Update order number</h3>
		<div style="width: 500px;">
		<p>Enter invoice number of a passive invoice on which you would like to update the order number.</p>
		</div>
		<form onsubmit="return false;" id="update-orderno-form">
		<p><strong>Invoice number:</strong>
		<input type="text" name="invno" size="10" value=""> (*)<br><br>
		<strong>New order number: </strong>
		<input type="text" name="newOrderno" size="10" value="">
		<input type="submit" value="Update order number">
		</p>
		</form>
	 </div>
	 <div id="invoice-address">
		<h3>Invoice Address</h3>
		<div style="width: 500px;">
		<p>Enter the invoice number of an invoice you would like see the address of.</p>
		</div>
		<form onsubmit="return false;" id="invoice-addr-form">
		<p><strong>Invoice number:</strong>
		<input type="text" name="invno" size="10" value=""> (*)
		<input type="submit" value="Get Address"></p>
		</form>
	 </div>
	 <div id="invoice-amount">
		<h3>Invoice Amount</h3>
		<div style="width: 500px;">
		<p>Enter the invoice number of an invoice you would like see the amount of.</p>
		</div>
		<form onsubmit="return false;" id="invoice-amount-form">
		<p><strong>Invoice number:</strong>
		<input type="text" name="invno" size="10" value=""> (*)
		<input type="submit" value="Get Amount"></p>
		</form>
	 </div>
	 <div id="invoice-part-amount">
		<h3>Get amount of invoice part</h3>
		<div style="width: 500px;">
		<p>Enter article number and quantity for each article you want to check.</p>
		</div>
		<form onsubmit="return false;" id="invoice-part-amount-form">
		<p><strong>Invoice number:</strong>
		<input type="text" name="invno" size="10" value=""> (*)</p>
		<p>Note: All Artno fields must contan a valid artno form the invoice, use quantity 0 if you only want to check one or two articles.</p>
		<p>Shipment and Handling fees will be included in the result.</p>
		<table>
		<tr>
		<th>Quantity</th>
		<th>Artno</th>
		</tr>
		<tr>
		<td><input size="3" name="qty0" /></td>
		<td><input size="10" name="artno0" /></td>
		</tr>
		<tr>
		<td><input size="3" name="qty1" /></td>
		<td><input size="10" name="artno1" /></td>
		</tr>
		<tr>
		<td><input size="3" name="qty2" /></td>
		<td><input size="10" name="artno2" /></td>
		</tr>
		<tr>
		<td colspan="4" align="right"><input type="submit" value="Get amount" /></td>
		</tr>
		</table>
		</form>
	 </div>
	 <div id="get-pclasses">
		<h3>Get pclasses</h3>
		<div style="width: 500px;">
		<p>Chose the currency you want to retreive the pclasses for.</p>
		</div>
		<form onsubmit="return false;" id="get-pclasses-form">
		<select name="currency">
		<option value="0">SEK</option>
		<option value="1">EUR</option>
		<option value="3">DKK</option>
		<option value="2">NOK</option>
		</select>
		<input type="submit" value="Retreive" />
		</form>
	 </div>
	 <div id="is-invoice-paid">
		<h3>Is invoice paid</h3>
		<div style="width: 500px;">
		<p>Enter the invoice number that you want to check.</p>
		</div>
		<form onsubmit="return false;" id="is-invoice-paid-form">
		<p><strong>Ocr:</strong>
		<input type="text" name="invno" size="10" value=""> (*)<br/>
		<input type="submit" value="check"></p>
		</form>
	 </div>
	</div>
    </div>
    <div id="fragment-4">
	<h2>Reservation functions</h2>
	
	<div id="tabs-4">
	 <ul>
	  <li><a href="#reserve-amount"><span>Reserve amount</span></a></li>
	  <li><a href="#activate-reservation"><span>Activate reservation</span></a></li>
	  <li><a href="#cancel-reservation"><span>Cancel reservation</span></a></li>
	  <li><a href="#split-reservation"><span>Split reservation</span></a></li>
	  <li><a href="#change-reservation"><span>Change reservation</span></a></li>
	  <li><a href="#reserve-ocr-numbers"><span>Reserve OCR numbers</span></a></li>
	 </ul>
	 
	 <div id="reserve-amount">
		<h3>Reserve amount</h3>
		<div style="width: 500px;">
		</div>
		<form onsubmit="return false;" name="reservation_form" id="reserve-amount-form">
		<table>
		<tr>
		 <td><b>Country</b></td>
		 <td><b>Approved</b></td>
		 <td><b>Not approved</b></td>
		</tr>
		<tr>
		 <td width="120">
		  <strong>Sweden</strong>
		 </td>
		 <td>
		  <input type="button" value="Karl Lidin" onclick="setrKarl();">
		  <input type="button" value="Kalle Anka AB" onclick="setrKalleAnka();">
		 </td>
		 <td>
		  <input type="button" value="Maud Johansson" onclick="setrMaud();">
		  <input type="button" value="Bj&ouml;rnligan AB" onclick="setrBjornligan();">
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Finland</strong>
		 </td>
		 <td>
		  <input type="button" value="Suvi Aurinkoinen" onclick="setrSuvi();">
		  <input type="button" value="Porin Mies-Laulu r.y." onclick="setrPorin();">
		 </td>
		 <td>
		  <input type="button" value="Mikael Miehinen" onclick="setrMikael();">
		  <input type="button" value="Mankalan Perhekodit Oy" onclick="setrMankalan();">
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Denmark</strong>
		 </td>
		 <td>
		  <input type="button" value="Rasmus Jens-Peter Lybert" onclick="setrRasmus();">
		  <input type="button" value="Onbase ApS" onclick="setrOnbase();">
		 </td>
		 <td>
		  <input type="button" value="Larsen & Olsen Contracters Aps" onclick="setrLarsenOlsen();">
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Norway</strong>
		 </td>
		 <td>
		  <input type="button" value="Petter Testmann" onclick="setrPetter();">
		 </td>
		 <td>
		  <input type="button" value="Petra Testdame" onclick="setrPetra();">
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Germany</strong>
		 </td>
		 <td>
		  <input type="button" value="Uno Eins" onclick="setrUnoEins();">
		 </td>
		 <td>
		  <input type="button" value="Uno Vier" onclick="setrUnoVier();">
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Netherlands</strong>
		 </td>
		 <td>
		  <input type="button" value="Test Persoon" onclick="setrTest();">
		 </td>
		</tr>
		</table>
		<p />
		<table>
		<tr>
		 <td>
		  <strong>SSN/Birth date:</strong>
		 </td>
		 <td>
		  <input type="text" name="pno" size="10" value=""> (*)
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Amount:</strong>
		 </td>
		 <td>
		  <input type="text" name="amount" size="10" value="50000"> (*)
		 </td>
		</tr>
		</table>
		<p />
		<b>Goods List</b><br />
		<table>
		<tr><td><b>Qty</b></td><td><b>Artno</b></td><td><b>Title</b></td><td><b>Price</b></td><td><b>VAT</b></td></tr>
		<tr>
		  <td><input type='text' name='qty1' value='1' style="width: 20px;"></td>
		  <td><input type='text' name='artno1' value='123' style="width: 80px;"></td>
		  <td><input type='text' name='title1' value='Item 1'></td>
		  <td><input type='text' name='price1' value='10000' style="width: 80px;"></td>
		  <td><input type='text' name='vat1' value='25' style="width: 30px;"></td>
		</tr>
		<tr>
		  <td><input type='text' name='qty2' value='2' style="width: 20px;"></td>
		  <td><input type='text' name='artno2' value='223' style="width: 80px;"></td>
		  <td><input type='text' name='title2' value='Item 2'></td>
		  <td><input type='text' name='price2' value='15000' style="width: 80px;"></td>
		  <td><input type='text' name='vat2' value='25' style="width: 30px;"></td>
		</tr>
		</table>
		</p>
		<p />
		<table><tr><td>
		<table>
		<tr>
		 <td>
		  <strong>Currency:</strong>
		 </td>
		 <td>
			<select name="currency">
			 <option value="0">Swedish kronor</option>
			 <option value="1">Norwegian kronor</option>
			 <option value="2">Euro</option>
			 <option value="3">Danish krona</option>
			 </select>
		 </td>
		</tr>
		<tr>
		 <td>		
		  Country:
		 </td>
		 <td>
			 <select name="country">
			 <option value="209">Sweden</option>
			 <option value="164">Norway</option>
			 <option value="73">Finland</option>
			 <option value="59">Denmark</option>
			 <option value="81">Germany</option>
			 <option value="154">Netherlands</option>
			 </select>
		 </td>
		</tr>
		<tr>
		 <td>
		  Language:
		 </td>
		 <td>
			 <select name="language">
			 <option value="138">Swedish</option>
			 <option value="97">Norwegian</option>
			 <option value="37">Finnish</option>
			 <option value="27">Danish</option>
			 <option value="28">German</option>
			 <option value="101">Dutch</option>
			 </select>
		 </td>
		</tr>
		<tr>
		 <td>
		  Pno encoding:
		 </td>
		 <td>
			 <select name="pnoencoding">
			 <option value="2">Swedish</option>
			 <option value="3">Norwegian</option>
			 <option value="4">Finnish</option>
			 <option value="5">Danish</option>
			 <option value="6">German</option>
			 <option value="7">Dutch</option>
			 </select>
		 </td>
		</tr>		
		<tr>
		 <td>
		  <strong>Reference:</strong>
		 </td>
		 <td>
		  <input type="text" name="reference" size="10" value="" />
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Reference code:</strong>
		 </td>
		 <td>
		  <input type="text" name="reference_code" size="10" value="" />
		 </td>
		</td>
		</tr>
		<tr>
		 <td>
		 <strong>Orderid 1:</strong>
		 </td>
		 <td>
		  <input type="text" name="orderid1" size="10" value="">
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Orderid 2:</strong>
		 </td>
		 <td>
		  <input type="text" name="orderid2" size="10" value="">
		 </td>
		</tr>
		</table>
		</td>
		</tr>
		<tr>
		<td valign="top">
		<table>
		<tr>
		 <td>
		  <strong>Delivery address:</strong>
		 </td>
		</tr>
		<tr>
		 <td>
		  First name:
		 </td>
		 <td>
		  <input type="text" name="lfname" size="20" value="">(*)
		 </td>
		</tr>
		<tr>
		 <td>
		  Last name:
		 </td>
		 <td>
		  <input type="text" name="llname" size="20" value="">(*)
		 </td>
		</tr>
		<tr>
		 <td>
		  Street address:
		 </td>
		 <td>
		  <input type="text" name="lstreet" size="20" value="">(*)
		 </td>
		</tr>
		<tr>
		 <td>
		  House num: 
		 </td>
		 <td>
		  <input type="text" name="lhousenum" size="20" value="">
		  <br />(* Germany & Netherlands only)
		 </td>
		</tr>
		<tr>
		 <td>
		  House ext: 
		 </td>
		 <td>
		  <input type="text" name="lhouseext" size="20" value="">
		  <br />(* Netherlands only)
		 </td>
		</tr>
		<tr>
		 <td>
		  Zip code:
		 </td>
		 <td>
	  	  <input type="text" name="lpostno" size="5" value=""> (*)
	  	 </td>
	  	</tr>
	  	<tr>
	  	 <td>
		  City:
		 </td>
		 <td>
		  <input type="text" name="lcity" size="20" value=""> (*)
		 </td>
		</tr>
		<tr>
		 <td>
		  Country:
		 </td>
		 <td>
			 <select name="lcountry">
			 <option value="se">Sweden</option>
			 <option value="no">Norway</option>
			 <option value="fi">Finland</option>
			 <option value="dk">Denmark</option>
			 <option value="de">Germany</option>
			 <option value="nl">Netherlands</option>
			</select>
		 </td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
		 <td>
		  <strong>Email:</strong>
		 </td>
		 <td>
		  <input type="text" name="email" size="20" value="">(*)
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Phone:</strong>
		 </td>
		 <td>
		  <input type="text" name="phone" size="20" value="">(*)
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Cellphone:</strong>
		 </td>
		 <td>
		  <input type="text" name="cell" size="20" value="">(*)
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Yearly salary:</strong>
		 </td>
		 <td>
		  <input type="text" name="ysalary" size="20" value="0" />
		  <br />(* Only for Danish part payments)
		 </td>
		</tr>		
		</table>
		
		</td>
		<td valign="top">
		
		<table>
		<tr>
		 <td>
		  <strong>Invoice address:</strong>
		 </td>
		</tr>
		<tr>
		 <td>
		  First name:
		 </td>
		 <td>
		  <input type="text" name="ffname" size="20" value="">(*)
		 </td>
		</tr>
		<tr>
		 <td>
		  Last name:
		 </td>
		 <td>
		  <input type="text" name="flname" size="20" value="">(*)
		 </td>
		</tr>
		<tr>
		 <td>
		  Street address:
		 </td>
		 <td>
		  <input type="text" name="fstreet" size="20" value="">(*)
		 </td>
		</tr>
		<tr>
		 <td>
		  House num:
		 </td>
		 <td>
		  <input type="text" name="fhousenum" size="20" value=""><br />
		   (*Germany & Netherlands only)
		 </td>
		</tr>
		<tr>
		 <td>
		  House ext: 
		 </td>
		 <td>
		  <input type="text" name="fhouseext" size="20" value=""><br />
		  (*Netherlands only)
		 </td>
		</tr>
		<tr>
		 <td>
		  Zip code:
		 </td>
		 <td>
		  <input type="text" name="fpostno" size="5" value=""> (*)
		 </td>
		</tr>
		<tr>
		 <td> 
		  City:
		 </td>
		 <td>
		  <input type="text" name="fcity" size="20" value=""> (*)
		 </td>
		</tr>
		<tr>
		 <td>
		  Country:
		 </td>
		 <td>
			 <select name="fcountry">
			 <option value="se">Sweden</option>
			 <option value="no">Norway</option>
			 <option value="fi">Finland</option>
			 <option value="dk">Denmark</option>
			 <option value="de">Germany</option>
			 <option value="nl">Netherlands</option>
			 </select>
		 </td>
		</tr>
		</table>
		
		</td>
		</tr>
		</table>
		
		<p />
		
		<input type="submit" value="Reserve amount">
		
		</form>
	 </div>
	 <div id="activate-reservation">
		<h3>Activate reservation</h3>
		<form onsubmit="return false;" name="reservation_form2" id="activate-reservation-form">
		
		<table>
		<tr>
		 <td><b>Country</b></td>
		 <td><b>Approved</b></td>
		 <td><b>Not approved</b></td>
		</tr>
		<tr>
		 <td width="120">
		  <strong>Sweden</strong>
		 </td>
		 <td>
		  <input type="button" value="Karl Lidin" onclick="setrKarl();">
		  <input type="button" value="Kalle Anka AB" onclick="setrKalleAnka();">
		 </td>
		 <td>
		  <input type="button" value="Maud Johansson" onclick="setrMaud();">
		  <input type="button" value="Björnligan AB" onclick="setrBjornligan();">
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Finland</strong>
		 </td>
		 <td>
		  <input type="button" value="Suvi Aurinkoinen" onclick="setrSuvi();">
		  <input type="button" value="Porin Mies-Laulu r.y." onclick="setrPorin();">
		 </td>
		 <td>
		  <input type="button" value="Mikael Miehinen" onclick="setrMikael();">
		  <input type="button" value="Mankalan Perhekodit Oy" onclick="setrMankalan();">
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Denmark</strong>
		 </td>
		 <td>
		  <input type="button" value="Rasmus Jens-Peter Lybert" onclick="setrRasmus();">
		  <input type="button" value="Onbase ApS" onclick="setrOnbase();">
		 </td>
		 <td>
		  <input type="button" value="Larsen & Olsen Contracters Aps" onclick="setrLarsenOlsen();">
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Norway</strong>
		 </td>
		 <td>
		  <input type="button" value="Petter Testmann" onclick="setrPetter();">
		 </td>
		 <td>
		  <input type="button" value="Petra Testdame" onclick="setrPetra();">
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Germany</strong>
		 </td>
		 <td>
		  <input type="button" value="Uno Eins" onclick="setrUnoEins();">
		 </td>
		 <td>
		  <input type="button" value="Uno Vier" onclick="setrUnoVier();">
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Netherlands</strong>
		 </td>
		 <td>
		  <input type="button" value="Test Persoon" onclick="setrTest();">
		 </td>
		</tr>
		</table>		
		
		<p><strong>Reservation no:</strong>
		<input type="text" name="rno" size="10" value=""> (*)<br/>
		<p>
		<table>
		<tr>
		 <td>
		  <strong>SSN/Birth date:</strong>
		 </td>
		 <td>
		  <input type="text" name="pno" size="10" value=""> (*)
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Amount:</strong>
		 </td>
		 <td>
		  <input type="text" name="amount" size="10" value="50000"> (*)
		 </td>
		</tr>
		</table>
		<p />
		<b>Goods List</b><br />
		<table>
		<tr><td><b>Qty</b></td><td><b>Artno</b></td><td><b>Title</b></td><td><b>Price</b></td><td><b>VAT</b></td></tr>
		<tr>
		  <td><input type='text' name='qty1' value='1' style="width: 20px;"></td>
		  <td><input type='text' name='artno1' value='123' style="width: 80px;"></td>
		  <td><input type='text' name='title1' value='Item 1'></td>
		  <td><input type='text' name='price1' value='10000' style="width: 80px;"></td>
		  <td><input type='text' name='vat1' value='25' style="width: 30px;"></td>
		</tr>
		<tr>
		  <td><input type='text' name='qty2' value='2' style="width: 20px;"></td>
		  <td><input type='text' name='artno2' value='223' style="width: 80px;"></td>
		  <td><input type='text' name='title2' value='Item 2'></td>
		  <td><input type='text' name='price2' value='15000' style="width: 80px;"></td>
		  <td><input type='text' name='vat2' value='25' style="width: 30px;"></td>
		</tr>
		</table>
		</p>
		<p />
		<table><tr><td>
		<table>
		<tr>
		 <td>
		  <strong>Currency:</strong>
		 </td>
		 <td>
			<select name="currency">
			 <option value="0">Swedish kronor</option>
			 <option value="1">Norwegian kronor</option>
			 <option value="2">Euro</option>
			 <option value="3">Danish krona</option>
			 </select>
		 </td>
		</tr>
		<tr>
		 <td>		
		  Country:
		 </td>
		 <td>
			 <select name="country">
			 <option value="209">Sweden</option>
			 <option value="164">Norway</option>
			 <option value="73">Finland</option>
			 <option value="59">Denmark</option>
			 <option value="81">Germany</option>
			 <option value="154">Netherlands</option>
			 </select>
		 </td>
		</tr>
		<tr>
		 <td>
		  Language:
		 </td>
		 <td>
			 <select name="language">
			 <option value="138">Swedish</option>
			 <option value="97">Norwegian</option>
			 <option value="37">Finnish</option>
			 <option value="27">Danish</option>
			 <option value="28">German</option>
			 <option value="101">Dutch</option>
			 </select>
		 </td>
		</tr>
		<tr>
		 <td>
		  Pno encoding:
		 </td>
		 <td>
			 <select name="pnoencoding">
			 <option value="2">Swedish</option>
			 <option value="3">Norwegian</option>
			 <option value="4">Finnish</option>
			 <option value="5">Danish</option>
			 <option value="6">German</option>
			 <option value="7">Dutch</option>
			 </select>
		 </td>
		</tr>	
		<tr>
		 <td>
		  <strong>Shipmenttype:</strong>
		 </td>
		 <td>
		  <select name="shipmenttype">
		   <option value="1">Normal</option>
		   <option value="2">Express</option>
		  </select>	
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Reference:</strong>
		 </td>
		 <td>
		  <input type="text" name="reference" size="10" value="" />
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Reference code:</strong>
		 </td>
		 <td>
		  <input type="text" name="reference_code" size="10" value="" />
		 </td>
		</td>
		</tr>
		<tr>
		 <td>
		 <strong>Orderid 1:</strong>
		 </td>
		 <td>
		  <input type="text" name="orderid1" size="10" value="">
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Orderid 2:</strong>
		 </td>
		 <td>
		  <input type="text" name="orderid2" size="10" value="">
		 </td>
		</tr>
		</table>
		</td>
		</tr>
		<tr>
		<td valign="top">
		<table>
		<tr>
		 <td>
		  <strong>Delivery address:</strong>
		 </td>
		</tr>
		<tr>
		 <td>
		  First name:
		 </td>
		 <td>
		  <input type="text" name="lfname" size="20" value="">(*)
		 </td>
		</tr>
		<tr>
		 <td>
		  Last name:
		 </td>
		 <td>
		  <input type="text" name="llname" size="20" value="">(*)
		 </td>
		</tr>
		<tr>
		 <td>
		  Street address:
		 </td>
		 <td>
		  <input type="text" name="lstreet" size="20" value="">(*)
		 </td>
		</tr>
		<tr>
		 <td>
		  House num: 
		 </td>
		 <td>
		  <input type="text" name="lhousenum" size="20" value="">
		  <br />(* Germany & Netherlands only)
		 </td>
		</tr>
		<tr>
		 <td>
		  House ext: 
		 </td>
		 <td>
		  <input type="text" name="lhouseext" size="20" value="">
		  <br />(* Netherlands only)
		 </td>
		</tr>
		<tr>
		 <td>
		  Zip code:
		 </td>
		 <td>
	  	  <input type="text" name="lpostno" size="5" value=""> (*)
	  	 </td>
	  	</tr>
	  	<tr>
	  	 <td>
		  City:
		 </td>
		 <td>
		  <input type="text" name="lcity" size="20" value=""> (*)
		 </td>
		</tr>
		<tr>
		 <td>
		  Country:
		 </td>
		 <td>
			 <select name="lcountry">
			 <option value="se">Sweden</option>
			 <option value="no">Norway</option>
			 <option value="fi">Finland</option>
			 <option value="dk">Denmark</option>
			 <option value="de">Germany</option>
			 <option value="nl">Netherlands</option>
			</select>
		 </td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
		 <td>
		  <strong>Email:</strong>
		 </td>
		 <td>
		  <input type="text" name="email" size="20" value="">(*)
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Phone:</strong>
		 </td>
		 <td>
		  <input type="text" name="phone" size="20" value="">(*)
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Cellphone:</strong>
		 </td>
		 <td>
		  <input type="text" name="cell" size="20" value="">(*)
		 </td>
		</tr>
		<tr>
		 <td>
		  <strong>Yearly salary:</strong>
		 </td>
		 <td>
		  <input type="text" name="ysalary" size="20" value="0" />
		  <br />(* Only for Danish part payments)
		 </td>
		</tr>		
		</table>
		
		</td>
		<td valign="top">
		
		<table>
		<tr>
		 <td>
		  <strong>Invoice address:</strong>
		 </td>
		</tr>
		<tr>
		 <td>
		  First name:
		 </td>
		 <td>
		  <input type="text" name="ffname" size="20" value="">(*)
		 </td>
		</tr>
		<tr>
		 <td>
		  Last name:
		 </td>
		 <td>
		  <input type="text" name="flname" size="20" value="">(*)
		 </td>
		</tr>
		<tr>
		 <td>
		  Street address:
		 </td>
		 <td>
		  <input type="text" name="fstreet" size="20" value="">(*)
		 </td>
		</tr>
		<tr>
		 <td>
		  House num:
		 </td>
		 <td>
		  <input type="text" name="fhousenum" size="20" value=""><br />
		   (*Germany & Netherlands only)
		 </td>
		</tr>
		<tr>
		 <td>
		  House ext: 
		 </td>
		 <td>
		  <input type="text" name="fhouseext" size="20" value=""><br />
		  (*Netherlands only)
		 </td>
		</tr>
		<tr>
		 <td>
		  Zip code:
		 </td>
		 <td>
		  <input type="text" name="fpostno" size="5" value=""> (*)
		 </td>
		</tr>
		<tr>
		 <td> 
		  City:
		 </td>
		 <td>
		  <input type="text" name="fcity" size="20" value=""> (*)
		 </td>
		</tr>
		<tr>
		 <td>
		  Country:
		 </td>
		 <td>
			 <select name="fcountry">
			 <option value="se">Sweden</option>
			 <option value="no">Norway</option>
			 <option value="fi">Finland</option>
			 <option value="dk">Denmark</option>
			 <option value="de">Germany</option>
			 <option value="nl">Netherlands</option>
			 </select>
		 </td>
		</tr>
		</table>
		
		</td>
		</tr>
		</table>
		<input type="submit" value="Activate Reservation"></p>
		</form>
	 </div>
	 <div id="cancel-reservation">
		<h3>Cancel reservation</h3>
		<div style="width: 500px;">
		<p>Enter reservation number of reservation to cancel</p>
		</div>
		<form onsubmit="return false;" id="cancel-reservation-form">
		<p><strong>Reservation no:</strong>
		<input type="text" name="rno" size="10" value=""> (*)<br/>
		<input type="submit" value="Cancel reservation"></p>
		</form>
	 </div>
	 <div id="split-reservation">
		<h3>Split reservation</h3>
		<div style="width: 500px;">
		<p>Enter reservation number and new amount.</p>
		</div>
		<form onsubmit="return false;" id="split-reservation-form">
		<p><strong>Reservation no:</strong>
		<input type="text" name="rno" size="10" value=""> (*)<br/>
		<p><strong>Split amount:</strong>
		<input type="text" name="amount" size="10" value=""> (*)<br/>
		<p><strong>Orderid 1:</strong>
		<input type="text" name="orderid1" size="10" value=""><br/>
		<p><strong>Orderid 2:</strong>
		<input type="text" name="orderid2" size="10" value=""><br/>
		<input type="submit" value="Split reservation"></p>
		</form>
	 </div>
	 <div id="change-reservation">
		<h3>Change reservation</h3>
		<div style="width: 500px;">
		<p>Enter reservation number and new amount.</p>
		</div>
		<form onsubmit="return false;" id="change-reservation-form">
		<p><strong>Reservation no:</strong>
		<input type="text" name="rno" size="10" value=""> (*)<br/>
		<p><strong>New amount:</strong>
		<input type="text" name="amount" size="10" value=""> (*)<br/>
		<input type="submit" value="Change reservation"></p>
		</form>
	 </div>
	 <div id="reserve-ocr-numbers">
		<h3>Reserve ocr numbers</h3>
		<div style="width: 500px;">
		<p>Enter the number of ocrnumbers that you want to reserve. (Swedish)</p>
		</div>
		<form onsubmit="return false;" id="reserve-ocrs-form">
		<p><strong>Number of ocrs:</strong>
		<input type="text" name="no" size="10" value=""> (*)<br/>
		<input type="submit" value="reserve"></p>
		</form>
	 </div>
	</div>
    </div>
</div>


  </div>
 </div>
</div>

</body>
</html>
