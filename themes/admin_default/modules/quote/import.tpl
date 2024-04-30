<!-- BEGIN: main -->
<link rel="stylesheet" href="{ASSETS_STATIC_URL}/js/select2/select2.min.css">
<script src="{ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<div class="panel panel-default">
    <div class="panel-body">
        <p>{LANG.excel_note_template}</p>
        <a class="btn btn-success" download href="{LINK_TEMPLATE}"><i class="fa fa-file-excel-o"></i> {LANG.excel_download_template}</a>
    </div>
</div>

<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->

<div class="panel panel-default">
    <div class="panel-body">
        <form method="post" action="{FORM_ACTION}" class="form-horizontal" enctype="multipart/form-data">
            <div class="form-group">
                <input type="file" name="import_file">
            </div>
            <div class="form-group">
                <div class="col-lg-6">
                    <select class="form-control" name="catids">
                        <option value="0" selected disabled>{LANG.please_select}</option>
                        <!-- BEGIN: cat -->
                        <option value="{CAT.key}"{CAT.selected}>{CAT.title}</option>
                        <!-- END: cat -->
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-6">
                    <select class="form-control" name="author_id">
                        <option value="0" selected disabled>{LANG.please_select}</option>
                        <!-- BEGIN: author -->
                        <option value="{AUTHOR.key}"{AUTHOR.selected}>{AUTHOR.name}</option>
                        <!-- END: author -->
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-6">
                    <select class="form-control" name="tagids[]" multiple data-placeholder="{LANG.please_select}">
                        <!-- BEGIN: tag -->
                        <option value="{TAG.key}"{TAG.selected}>{TAG.title}</option>
                        <!-- END: tag -->
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="checkbox">
                    <label><input type="checkbox" name="truncate_data" value="1"{DATA.truncate_data}> <strong class="text-danger">{LANG.excel_truncate}</strong></label>
                </div>
            </div>
            <input type="hidden" name="save" value="{NV_CHECK_SESSION}">
            <button type="submit" class="btn btn-primary"><i class="fa fa-cloud-upload"></i> {LANG.excel_import}</button>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('select').select2({
            placeholder: '{LANG.please_select}',
            tags: true,
            autoClear: true,
        });
    });
</script>

<!-- END: main -->
