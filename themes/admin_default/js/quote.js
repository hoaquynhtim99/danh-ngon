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

function get_authour_alias(id, checksess) {
    var name_author = strip_tags(document.getElementById('element_name_author').value);
    if (name_author != '') {
        $.post(
            script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=authour&nocache=' + new Date().getTime(),
            'changealias=' + checksess + '&name_author=' + encodeURIComponent(name_author) + '&id=' + id, function(res) {
                if (res != "") {
                    document.getElementById('element_alias').value = res;
                } else {
                    document.getElementById('element_alias').value = '';
                }
            });
    }
}

function nv_change_authour_weight(id, checksess) {
    var new_weight = $('#change_weight_' + id).val();
    $('#change_weight_' + id).prop('disabled', true);
    $.post(
        script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=authour&nocache=' + new Date().getTime(),
        'changeweight=' + checksess + '&id=' + id + '&new_weight=' + new_weight, function(res) {
            $('#change_weight_' + id).prop('disabled', false);
            var r_split = res.split("_");
            if (r_split[0] != 'OK') {
                alert(nv_is_change_act_confirm[2]);
            }
            location.reload();
        });
}

function nv_delele_authors(id, checksess) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(
            script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=authour&nocache=' + new Date().getTime(),
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

function nv_content_action(oForm, checkss, msgnocheck) {
    var fa = oForm['idcheck[]'];
    var listid = '';
    if (fa.length) {
        for (var i = 0; i < fa.length; i++) {
            if (fa[i].checked) {
                listid = listid + fa[i].value + ',';
            }
        }
    } else {
        if (fa.checked) {
            listid = listid + fa.value + ',';
        }
    }

    if (listid != '') {
        var action = document.getElementById('action-of-content').value;
        if (action == 'delete_all') {
            if (confirm(nv_is_del_confirm[0])) {
                $.post(
                    script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=authour&nocache=' + new Date().getTime(),
                    'delete_all=' + checkss + '&listid=' + listid, function(res) {
                        var r_split = res.split("_");
                        if (r_split[0] == 'OK') {
                            location.reload();
                        } else {
                            alert(nv_is_del_confirm[2]);
                        }
                    });
            }
        }
    } else {
        alert(msgnocheck);
    }
}

function nv_change_content_status(id, checksess) {
    $('#change_status' + id).prop('disabled', true);
    $.post(
        script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(),
        'changestatus=' + checksess + '&id=' + id, function(res) {
            $('#change_status' + id).prop('disabled', false);
            if (res != 'OK') {
                alert(nv_is_change_act_confirm[2]);
                location.reload();
            }
        });
}
