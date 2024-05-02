<!-- BEGIN: main -->
<link type="text/css" href="{NV_STATIC_URL}themes/default/images/{MODULE_FILE}/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" />
<link type="text/css" href="{NV_STATIC_URL}themes/default/images/{MODULE_FILE}/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_STATIC_URL}themes/default/images/{MODULE_FILE}/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="{NV_STATIC_URL}themes/default/images/{MODULE_FILE}/bootstrap-datepicker/locales/bootstrap-datepicker.{NV_LANG_INTERFACE}.min.js"></script>
<!-- BEGIN: error -->
<div class="alert-danger alert">{ERROR}</div>
<!-- END: error -->
<div class="row">
    <div class="col-lg-18">
        <form method="get" action="{NV_BASE_ADMINURL}index.php">
            <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}">
            <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}">
            <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="element_q">{LANG.search_keywords}:</label>
                        <input type="text" class="form-control" id="element_q" name="q" value="{SEARCH.q}" placeholder="{LANG.search_note}">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="element_from">{LANG.from_day}:</label>
                        <input type="text" class="form-control datepicker" id="element_from" name="f" value="{SEARCH.from}" placeholder="dd-mm-yyyy" autocomplete="off">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="element_to">{LANG.to_day}:</label>
                        <input type="text" class="form-control datepicker" id="element_to" name="t" value="{SEARCH.to}" placeholder="dd-mm-yyyy" autocomplete="off">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="visible-sm-block visible-md-block visible-lg-block">&nbsp;</label>
                        <button class="btn btn-primary" type="submit"><i class="fa fa-search" aria-hidden="true"></i> {GLANG.search}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-lg-6">
        <div class="form-group text-right">
            <label class="visible-sm-block visible-md-block visible-lg-block">&nbsp;</label>
            <a href="{LINK_ADD_NEW}" class="btn btn-success"><i class="fa fa-plus-circle" aria-hidden="true"></i> {LANG.add_author}</a>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('.datepicker').datepicker({
        language: '{NV_LANG_INTERFACE}',
        format: 'dd-mm-yyyy',
        weekStart: 1,
        todayBtn: 'linked',
        autoclose: true,
        todayHighlight: true,
        zIndexOffset: 1000
    });
});
</script>
<form>
    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered">
            <thead>
            <tr>
                <th style="width: 1%" class="text-center">
                    <input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);">
                </th>
                <th style="width: 40%" class="text-nowrap">
                    <a href="{URL_ORDER_NAME_AUTHOR}">{ICON_ORDER_TITLE} {LANG.name_author}</a>
                </th>
                <th style="width: 15%" class="text-center text-nowrap">{LANG.image_author}</th>
                <th style="width: 15%" class="text-nowrap">
                    <a href="{URL_ORDER_ADDTIME}">{ICON_ORDER_ADD_TIME} {LANG.addtime}</a>
                </th>
                <th style="width: 15%" class="text-nowrap">
                    <a href="{URL_ORDER_UPDATETIME}">{ICON_ORDER_EDIT_TIME} {LANG.edittime}</a>
                </th>
                <th style="width: 14%" class="text-nowrap text-center">{LANG.function}</th>
            </tr>
            </thead>
            <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td class="text-center">
                    <input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]">
                </td>
                <td><strong>{ROW.name_author}</strong></td>
                <td class="text-center">
                    <a onclick="showAuthorImage('{ROW.image_upload}'); return false;" style="cursor: pointer;" class="img__popup">
                        <img src="{ROW.image}" class="img-thumbnail img__author">
                    </a>
                </td>
                <td class="text-nowrap">{ROW.addtime}</td>
                <td class="text-nowrap">{ROW.updatetime}</td>
                <td class="text-center text-nowrap">
                    <a href="{ROW.url_edit}" class="btn btn-sm btn-default"><i class="fa fa-edit"></i> {GLANG.edit}</a>
                    <a class="btn btn-sm btn-danger" href="javascript:void(0);" onclick="nv_delele_authors('{ROW.id}', '{NV_CHECK_SESSION}');"><i class="fa fa-trash"></i> {GLANG.delete}</a>
                </td>
            </tr>
            <!-- END: loop -->
            </tbody>
            <!-- BEGIN: generate_page -->
            <tfoot>
            <tr>
                <td colspan="6">
                    {GENERATE_PAGE}
                </td>
            </tr>
            </tfoot>
            <!-- END: generate_page -->
        </table>
    </div>
    <div class="form-group form-inline">
        <div class="form-group">
            <select class="form-control" id="action-of-content">
                <option value="delete_all">{GLANG.delete}</option>
            </select>
        </div>
        <button type="button" class="btn btn-primary" onclick="nv_author_action(this.form, '{NV_CHECK_SESSION}', '{LANG.msgnocheck}')">{GLANG.submit}</button>
    </div>
</form>
<script>
    function showAuthorImage(imageSrc) {
        var imageHtml = '<img src="' + imageSrc + '" class="img-fluid img-responsive">';
        modalShow('{LANG.image_author}', imageHtml);
    }
</script>

<!-- END: main -->
