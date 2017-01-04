<?php
$shop = $vars['shop'];
$date = $vars['date'];
$products = $vars['products'];
?>
<table id="tableTicket">
	<thead>
		<tr>
			<th><?=$shop ?></th>
			<th colspan="2"><?=$date ?></th>
		</tr>		
		<tr>
			<th><?=elgg_echo('ticketup:table:product')?></th>
			<th><?=elgg_echo('ticketup:table:quantity')?></th>
			<th><?=elgg_echo('ticketup:table:price')?></th>
		</tr>		
	</thead>
	<tbody>
		<?php
		foreach ($products as $product) {		
			echo "<tr>";
				echo "<td>";
					echo $product->name;
				echo "</td>";
				echo "<td>";
					echo $product->quantity;
				echo "</td>";
				echo "<td>";
					echo $product->price;
				echo "</td>";
			echo "</tr>";
		}
		?>
	</tbody>
</table>