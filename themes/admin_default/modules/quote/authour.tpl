<!-- BEGIN: main -->
<link type="text/css" href="{NV_STATIC_URL}themes/default/images/{MODULE_FILE}/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" />
<link type="text/css" href="{NV_STATIC_URL}themes/default/images/{MODULE_FILE}/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_STATIC_URL}themes/default/images/{MODULE_FILE}/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="{NV_STATIC_URL}themes/default/images/{MODULE_FILE}/bootstrap-datepicker/locales/bootstrap-datepicker.{NV_LANG_INTERFACE}.min.js"></script>
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
                        <input type="text" class="form-control" id="element_q" name="q" value="{SEARCH.q}" placeholder="{LANG.enter_search_key}">
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
            <a href="{LINK_ADD_NEW}" class="btn btn-success"><i class="fa fa-plus-circle" aria-hidden="true"></i> {LANG.add_authour}</a>
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
                    <a href="{URL_ORDER_NAME_AUTHOR}">{ICON_ORDER_TITLE} {LANG.title}</a>
                </th>
                <th style="width: 15%" class="text-center text-nowrap">{LANG.image_authour}</th>
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
                    <img src="{ROW.image}" class="img-thumbnail img__authour"  onclick="showImagePopup('{ROW.image_upload}')">
                </td>
                <td class="text-nowrap">{ROW.addtime}</td>
                <td class="text-nowrap">{ROW.updatetime}</td>
                <td class="text-center text-nowrap">
                    <a href="{ROW.url_edit}" class="btn btn-xs btn-default"><i class="fa fa-edit"></i> {GLANG.edit}</a>
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
        <button type="button" class="btn btn-primary" onclick="nv_authour_action(this.form, '{NV_CHECK_SESSION}', '{LANG.msgnocheck}')">{GLANG.submit}</button>
    </div>
</form>
<div id="imagePopup" style="display:none; position:fixed; z-index:1001; left:0; top:0; width:100%; height:100%; background-color: rgba(0,0,0,0.5);" onclick="closePopupIfOutside(event)">
    <span style="position:absolute; right:20px; top:20px; cursor:pointer; color:#fff; font-size:30px;" onclick="closePopup()">&times;</span>
    <img id="popupImg" class="img-thumbnail" style="max-width:90%; max-height:80%; margin:auto; position:absolute; left:0; right:0; top:10%; bottom:10%;" onclick="event.stopPropagation()">
</div>
<script>
    function showImagePopup(src) {
        document.getElementById('popupImg').src = src;
        document.getElementById('imagePopup').style.display = 'block';
    }

    function closePopup() {
        document.getElementById('imagePopup').style.display = 'none'; // Ẩn popup
    }

    function closePopupIfOutside(event) {
        if (event.target === document.getElementById('imagePopup')) {
            closePopup();
        }
    }
</script>
<!-- END: main -->
