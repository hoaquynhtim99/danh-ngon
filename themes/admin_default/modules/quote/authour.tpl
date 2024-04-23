<!-- BEGIN: main -->
<script src="{ASSETS_STATIC_URL}/editors/ckeditor/ckeditor.js"></script>
<!-- BEGIN: error -->
<div class="alert alert-danger">
    {ERROR}
</div>
<!-- END: error -->
<!-- BEGIN: search -->
<div class="well row">
    <form action="{URL}" method="GET">
        <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}"/>
        <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}"/>
        <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}"/>
        <div class="col-sm-6">
            <div class="form-group">
                <label><strong>{LANG.name_authour}</strong></label>
                <input type="text" class="form-control" name="q" value="{SEARCH.q}">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label class="visible-sm-block visible-md-block visible-lg-block">&nbsp;</label>
                <a href="{URL}&request=1" class="btn btn-success"><i class="fa fa-plus"></i> {LANG.add_authour}</a>
                <button type="submit" name="search" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> {GLANG.search}</button>
            </div>
        </div>
    </form>
</div>
<!-- END: search -->
<!-- BEGIN: request -->
<form method="post">
    <table class="table table-hover table-striped table-bordered">
        <caption><strong><em class="fa fa-file-text-o"></em> {CAPTION}</strong></caption>
        <tbody>
        <tr>
            <td class="text-center">{LANG.name_authour}</td>
            <td><input class="form-control" name="name_author" type="text" value="{DATA.name_author}"></td>
        </tr>
        <tr>
            <td class="text-center">{LANG.description_authour}</td>
            <td><textarea class="form-control" name="description">{DATA.description}</textarea></td>
        </tr>
        <tr>
            <td class="text-center">{LANG.bodyhtml_authour}</td>
            <td>
                <textarea class="form-control" name="bodyhtml">{DATA.bodyhtml}</textarea>
            </td>
        </tr>
        <tr>
            <td class="text-center">{LANG.image_authour}</td>
            <td>
                <div class="mb-0">
                    <div class="input-group">
                        <input class="form-control image image_{ROW.id_employee}_1" type="text" name="image" id="image" placeholder="{LANG.select_image}" readonly value="{DATA.image}">
                        <span class="input-group-btn">
                            <button type="button" name="selectimg" onclick="openImageBrowser()" class="btn btn-info btn__image"><em class="fa fa-folder-open-o"></em></button>
                        </span>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="2" class="text-center">
                <a href="{URL}" class="btn btn-default">{LANG.url_cancel}</a>
                <button type="submit" name="save" class="btn btn-primary">{LANG.action}</button>
            </td>
        </tr>
        </tfoot>
    </table>
</form>
<!-- END: request -->
<form method="post" id="form__list">
    <div>
        <hr>
        <br>
        <h2 class="text-center"><strong>{LANG.list_authour}</strong></h2>
        <p>{LANG.total_db} <span class="btn btn-warning">{TOTAL}</span></p>
        <table class="table table-bordered table-striped table-hover">
            <thead class="bg-primary">
            <tr>
                <th class="text-center"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);"</th>
                <th class="text-center">{LANG.weight}</th>
                <th class="text-center">{LANG.name_authour}</th>
                <th class="text-center">{LANG.description_authour}</th>
                <th class="text-center">{LANG.image_authour}</th>
                <th class="text-center">{LANG.function}</th>
            </thead>
            <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td class="text-center">
                    <input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]" />
                </td>
                <td class="text-center">{ROW.stt}</td>
                <td class="text-center">{ROW.name_author}</td>
                <td class="text-center">{ROW.description}</td>
                <td class="text-center">
                    <img src="{ROW.image}" class="img-thumbnail" style="width: 100px;">
                </td>
                <td class="text-center">
                    <a href="{ROW.url_edit}" class="btn btn-sm btn-default"><i class="fa fa-edit"></i>{GLANG.edit}</a>
                    <a href="{ROW.url_delete}" class="btn btn-sm btn-danger"  onclick="return confirm('{LANG.confirm_delete}')"><i class="fa fa-trash"></i> {GLANG.delete}</a>
            </tr>
            <!-- END: loop -->
            </tbody>
        </table>
    </div>
    <div class="form-group form-inline">
        <div class="form-group">
            <select name="btn_delete" class="form-control">
                <option value="btn_delete">{GLANG.delete}</option>
            </select>
        </div>
        <button type="button" class="btn btn-primary" onclick="confirmDelete('{LANG.confirm_delete_all}')" >{GLANG.delete}</button>
    </div>
</form>
<!-- BEGIN: generate_page -->
<div class="text-center">
    {GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<script>
    CKEDITOR.replace('bodyhtml');
</script>
<script>
    function openImageBrowser() {
        $("button[name=selectimg]").click(function () {
            var area = "image"; //id của thẻ input lưu đường dẫn file
            var alt = "imagealt"; //id của thẻ input lưu tiêu đề file
            var path = '{NV_UPLOADS_DIR}/{MODULE_UPLOAD}';
            var type = "image"; // kiểu định dạng cho phép upload
            var currentpath = '{NV_UPLOADS_DIR}/{MODULE_UPLOAD}'; //uploads/sample/2020
            nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&alt=" + alt + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
            return false;
        });
    }
</script>
<!-- END: main -->
