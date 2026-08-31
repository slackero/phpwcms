<?php
$be_support = isset($GLOBALS['phpwcms']['support']) ? $GLOBALS['phpwcms']['support'] : [];
if (!empty($be_support['address']) || !empty($be_support['email']) || !empty($be_support['phone']) || !empty($be_support['name'])) {
?>
<!-- Impressum -->
<div class="dashboard card">
	<div class="card-header">
		<h2><?php echo $BL['be_dashboard_support']; ?></h2>
	</div>
	<div class="card-body">
		<?php
		echo '<p><strong>' . html_specialchars(!empty($be_support['name']) ? $be_support['name'] : 'Support') . '</strong><br>';
		if (!empty($be_support['address'])) {
			echo nl2br(html_specialchars($be_support['address']));
		}
		echo '</p>';
		if (!empty($be_support['phone']) || !empty($be_support['email'])) {
			echo '<p>';
			if (!empty($be_support['phone'])) {
				echo '<a href="tel:' . html_specialchars($be_support['phone']). '">';
                echo '<i class="fa-solid fa-phone me-2"></i>';
                echo html_specialchars($be_support['phone']) . '</a><br>';
			}
			if (!empty($be_support['email'])) {
				echo '<a href="mailto:' . html_specialchars($be_support['email']) . '">';
                echo '<i class="fa-solid fa-envelope me-2"></i>';
                echo html_specialchars($be_support['email']) . '</a>';
			}
			echo '</p>';
		}
		?>
	</div>
</div>
<?php
}
?>

<div class="text-end text-muted mt-3" style="font-size: 0.8em;">
	<a href="https://github.com/slackero/phpwcms" target="_blank" rel="noopener noreferrer">phpwcms <?php echo PHPWCMS_VERSION ?></a> &copy; 2002&#8212;<?php echo date('Y'); ?> Oliver Georgi.
	<a href="phpwcms.php?do=about" title="<?php echo $BL['be_aboutlink_title'] ?>">Extensions</a> are copyright of their respective owners.
</div>
