<!-- BEGIN: main -->
<div class="row">
    <div class="col-lg-16">
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
                        <label class="visible-sm-block visible-md-block visible-lg-block">&nbsp;</label>
                        <button class="btn btn-primary" type="submit"><i class="fa fa-search" aria-hidden="true"></i> {GLANG.search}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-lg-8">
        <div class="form-group text-right">
            <label class="visible-sm-block visible-md-block visible-lg-block">&nbsp;</label>
            <button class="btn btn-success m-bottom" type="button" data-toggle="modal" data-target="#addTagModal" onclick="addtagModal()"><i class="fa fa-plus-circle" aria-hidden="true"></i> {LANG.add_tag}</button>
            <button class="btn btn-success m-bottom" type="button" data-toggle="modal" data-target="#addTagModalAll"><i class="fa fa-plus-circle" aria-hidden="true"></i> {LANG.add_tags_all}</button>
        </div>
    </div>
</div>

<form>
    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered">
            <thead class="bg-primary">
            <tr>
                <th style="width: 1%" class="text-center">
                    <input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);">
                </th>
                <th style="width: 25%" class="text-nowrap">{LANG.title}</th>
                <th style="width: 1%" class="text-center text-nowrap">{LANG.description}</th>
                <th style="width: 15%" class="text-center text-nowrap">{LANG.keywords}</th>
                <th style="width: 14%" class="text-nowrap text-center">{LANG.function}</th>
            </tr>
            </thead>
            <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td class="text-center">
                    <input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]">
                </td>
                <td><strong>{ROW.title}</strong></td>
                <td class="text-center">
                    <!-- BEGIN: complete -->
                    <em class="text-success fa fa-check"></em>
                    <!-- END: complete -->
                    <!-- BEGIN: incomplete -->
                    <em class="text-danger fa fa-warning tags-tip" data-toggle="tooltip" data-placement="top" title="{LANG.tags_no_description}"></em>
                    <!-- END: incomplete -->
                </td>
                <td class="text-center">{ROW.keywords}</td>
                <td class="text-center text-nowrap">
                    <button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#addTagModal"
                            data-id="{ROW.id}" data-title="{ROW.title}" data-alias="{ROW.alias}" data-description="{ROW.description}"
                            data-keywords="{ROW.keywords}" data-image="{ROW.image}"
                            onclick="setDataToModal(this)">
                        <i class="fa fa-pencil"></i> {GLANG.edit}
                    </button>
                    <a class="btn btn-sm btn-danger" href="javascript:void(0);" onclick="nv_delele_tags('{ROW.id}', '{NV_CHECK_SESSION}');"><i class="fa fa-trash"></i> {GLANG.delete}</a>
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
        <button type="button" class="btn btn-primary" onclick="nv_tag_action(this.form, '{NV_CHECK_SESSION}', '{LANG.msgnocheck}')">{GLANG.submit}</button>
    </div>
</form>
<div class="modal fade" id="addTagModal" tabindex="-1" role="dialog" aria-labelledby="addTagModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" enctype="multipart/form-data" id="formTag">
                <input type="hidden" name="id" value="{ROW.id}">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="addTagModalLabel"></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-sm-6 control-label" for="element_title">{LANG.title}: </label>
                        <div class="col-sm-18 col-lg-10">
                            <input type="text" name="title" class="form-control" id="element_title">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-6 control-label" for="element_alias">{LANG.alias}</label>
                        <div class="col-sm-18 col-lg-10">
                            <div class="input-group">
                                <input type="text" class="form-control" id="element_alias" name="alias">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" onclick="get_tag_alias('{DATA.id}', '{NV_CHECK_SESSION}')">
                                        <i class="fa fa-retweet"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-6 control-label" for="element_description">{LANG.description}</label>
                        <div class="col-sm-18 col-lg-10">
                            <textarea class="form-control" id="element_description" name="description" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-6 control-label" for="element_keywords">{LANG.keywords}: </label>
                        <div class="col-sm-18 col-lg-10">
                            <input type="text" name="keywords" class="form-control" id="element_keywords">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-6 control-label" for="element_image">{LANG.image_illustration}:</label>
                        <div class="col-sm-18 col-lg-10">
                            <div class="input-group">
                                <input type="text" id="element_image" name="image" value="{DATA.image}" class="form-control">
                                <span class="input-group-btn">
                            <button class="btn btn-default" type="button" id="element_image_pick"><i class="fa fa-file-image-o"></i></button>
                        </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-18 col-sm-offset-6">
                            <input type="hidden" name="save_tag" value="{NV_CHECK_SESSION}">
                            <button type="button" class="btn btn-primary" id="submitTagBtn">
                                <i class="fa fa-floppy-o"></i>
                                {GLANG.submit}</button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="addTagModalAll" tabindex="-1" role="dialog" aria-labelledby="addTagModalAllLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" enctype="multipart/form-data" id="formTagAll">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="addTagModalAllLabel">{LANG.add_tags_all}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label" for="element_title">{LANG.note_tags}: </label>
                        <textarea class="form-control" name="title" id="element_title_all" rows="5"></textarea>

                    </div>
                    <div class="row">
                        <div class="col-sm-18 col-sm-offset-6">
                            <input type="hidden" name="save_tag_all" value="{NV_CHECK_SESSION}">
                            <button type="button" class="btn btn-primary" id="submitTagBtnAll">
                                <i class="fa fa-floppy-o"></i>
                                {GLANG.submit}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('.tags-tip').tooltip();
    });
</script>
<!-- BEGIN: getalias -->
<script type="text/javascript">
    $(document).ready(function() {
        var autoAlias = true;
        $('#element_title').on('change', function() {
            if (autoAlias) {
                get_tag_alias('{DATA.id}', '{NV_CHECK_SESSION}');
            }
        });
        $('#element_alias').on('keyup', function() {
            if (trim($(this).val()) == '') {
                autoAlias = true;
            } else {
                autoAlias = false;
            }
        });
    });
</script>
<!-- END: getalias -->
<script type="text/javascript">
    $(document).ready(function(){
        $('#element_image_pick').on('click', function(e) {
            e.preventDefault();
            nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=element_image&path={UPLOAD_PATH}&type=image&currentpath={UPLOAD_CURRENT}", "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
        });
    });
</script>
<script type="text/javascript">
    function setDataToModal(button) {
        var id = button.getAttribute('data-id');
        var title = button.getAttribute('data-title');
        var alias = button.getAttribute('data-alias');
        var description = button.getAttribute('data-description');
        var keywords = button.getAttribute('data-keywords');
        var image = button.getAttribute('data-image');

        document.getElementById('element_title').value = title;
        document.getElementById('element_alias').value = alias;
        document.getElementById('element_description').value = description;
        document.getElementById('element_keywords').value = keywords;
        document.getElementById('element_image').value = image;
        document.getElementById('addTagModal').querySelector('input[name="id"]').value = id;

        document.getElementById('addTagModalLabel').innerHTML = '{LANG.edit_tag}';
    }
    function addtagModal() {
        document.getElementById('element_title').value = '';
        document.getElementById('element_alias').value = '';
        document.getElementById('element_description').value = '';
        document.getElementById('element_keywords').value = '';
        document.getElementById('element_image').value = '';
        document.getElementById('addTagModal').querySelector('input[name="id"]').value = '';
        document.getElementById('addTagModalLabel').innerHTML = '{LANG.add_tag}';
    }
</script>
<script>
    $(document).ready(function() {
        $('#submitTagBtn').click(function() {
            var $form = $('#formTag');
            $.ajax({
                url: location.href,
                type: 'POST',
                data: $form.serialize(),
            }).done(function(response) {
                if (response['res'] == 'success') {
                    $('#addTagModal').modal('hide');
                    location.reload();
                } else {
                    alert(response['mess']);
                }
            })
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#submitTagBtnAll').click(function() {
            var $form = $('#formTagAll');
            $.ajax({
                url: location.href,
                type: 'POST',
                data: $form.serialize(),
            }).done(function(response) {
                if (response['res'] == 'success') {
                    $('#addTagModalAll').modal('hide');
                    alert(response['mess']);
                    location.reload();
                } else {
                    alert(response['mess']);
                }
            })
        });
    });
</script>
<!-- END: main -->
