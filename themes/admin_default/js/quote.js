/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function nv_change_cats_weight(id, checksess) {
    var new_weight = $('#change_weight_' + id).val();
    $('#change_weight_' + id).prop('disabled', true);
    $.post(
        script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cats&nocache=' + new Date().getTime(),
        'changeweight=' + checksess + '&id=' + id + '&new_weight=' + new_weight, function(res) {
        $('#change_weight_' + id).prop('disabled', false);
        var r_split = res.split("_");
        if (r_split[0] != 'OK') {
            alert(nv_is_change_act_confirm[2]);
        }
        location.reload();
    });
}

function nv_change_cats_status(id, checksess) {
    $('#change_status' + id).prop('disabled', true);
    $.post(
        script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cats&nocache=' + new Date().getTime(),
        'changestatus=' + checksess + '&id=' + id, function(res) {
        $('#change_status' + id).prop('disabled', false);
        if (res != 'OK') {
            alert(nv_is_change_act_confirm[2]);
            location.reload();
        }
    });
}

function nv_delele_cats(id, checksess) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(
            script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cats&nocache=' + new Date().getTime(),
            'delete=' + checksess + '&id=' + id, function(res) {
            var r_split = res.split("_");
            if (r_split[0] == 'OK') {
                location.reload();
            } else {
                alert(nv_is_del_confirm[2]);
            }
        });
    }
}

function confirmDelete() {
    if (confirm('Bạn có chắc chắn muốn xóa không các danh sách đã ch?')) {
        $.ajax({
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=authour',
            type: "POST",
            data: $('#form__list').serialize(),
            success: function (data) {
                location.reload();
            }
        });
    }
}
