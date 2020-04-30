<style type="text/css">
.ig_es_offer{
    width: 60%;
    margin: 0 auto;
    text-align: center;
    padding-top: 1.2em;
}
.ig_es_offer img{
	/*width: 100%;*/
}
</style>
<?php 
if(  (get_option('ig_es_offer_bfcm_done_2019') !== 'yes') && ($ig_current_date >= strtotime("2019-11-22")) && ($ig_current_date <= strtotime("2019-11-30")) ) { ?>
	<div class="ig_es_offer">
	    <a target="_blank" href="?es_dismiss_admin_notice=1&option_name=offer_bfcm_done_2019"><img src="<?php echo ES_PLUGIN_URL ?>/lite/admin/images/bfcm-2019.png"  /></a>
	</div>

<?php } 
if( (get_option('ig_es_offer_last_day_bfcm_done_2019') !== 'yes') &&  ($ig_current_date >= strtotime("2019-12-02")) && ($ig_current_date <= strtotime("2019-12-03")) ) { ?>
	<div class="ig_es_offer">
	    <a target="_blank" href="?es_dismiss_admin_notice=1&option_name=offer_last_day_bfcm_done_2019"><img src="<?php echo ES_PLUGIN_URL ?>/lite/admin/images/bfcm-last-2019.png"  /></a>
	</div>

<?php } ?>
