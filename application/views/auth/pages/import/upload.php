<style type="text/css">

label input[type=text]{
    width:25px;
}

</style>
<div id="form">
    <form method="post" enctype="multipart/form-data" action="<?php echo site_url('admin/import/upload')?>">
    Select file (.xls/.xlsx) :
    <br /><br />
    <input type="file" name="userfile" size="50" class="form"  />
    <br /><br />
    Merchant ID : <span id="merchant_id_txt"><?php print $merchant_id?></span><br />
    <input type="hidden" id="merchant_id" name="merchant_id"  value="<?php print $merchant_id?>" />
    <input type="hidden" id="merchant_fullname" name="merchant_fullname"  value="<?php print $merchant_fullname ?>" />
    <input type="hidden" id="merchant_email" name="merchant_email"  value="<?php print $merchant_email; ?>" />
    <input type="hidden" id="merchant_name" name="merchant_name" value="<?php print $merchant_name?>" />
    <label for="header_index">Column Label Row Number
        <input type="text" value="<?php print $this->config->item('import_label_default')?>" id="label_index" name="label_index" /><br />
    </label>
    <label for="header_index">Header Row Number
        <input type="text" value="<?php print $this->config->item('import_header_default')?>" id="header_index" name="header_index" /><br />
    </label>
    <label for="data_index">Data Row Starts at Number
        <input type="text" value="<?php print $this->config->item('import_data_default')?>" id="data_index" name="data_index" /><br />
    </label>

    <br /><br />
    <input type="submit" value="Upload" name="upload" />
    </form>
</div>
