/* 
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

jQuery(document).ready(function () {

    var toggle_advanced = false;
    var toggle_subtask = false;

    jQuery('.mytask').click(function () {
        jQuery(this).closest('form').submit();
    });

    jQuery('.futuretask').click(function () {
        jQuery(this).closest('form').submit();
    });

    jQuery('.search_start_date').change(function () {
        jQuery(this).closest('form').submit();
    });

    jQuery('.search_status').change(function () {
        jQuery(this).closest('form').submit();
    });

    jQuery('.search_project_id').change(function () {
        jQuery(this).closest('form').submit();
    });

    jQuery('.search_type_id').change(function () {
        jQuery(this).closest('form').submit();
    });

    jQuery('.search_milestone_id').change(function () {
        jQuery(this).closest('form').submit();
    });

    jQuery('.search_priority_id').change(function () {
        jQuery(this).closest('form').submit();
    });

    jQuery('.task-assigned-to input').click(function () {
        return assignTask(jQuery(this));
    });

    jQuery('.manage-subtasks-link').click(function () {

        if (toggle_subtask) {
            jQuery('.manage-subtasks').hide();
            toggle_subtask = false;
        } else {
            jQuery('.manage-subtasks').show();
            toggle_subtask = true;
        }

        is_modified = true;
        jQuery('.task-submit-button').show();
    });


    jQuery('.advanced-search').click(function () {

        if (toggle_advanced) {
            jQuery('.all-fields').hide();
            toggle_advanced = false;
        } else {
            jQuery('.all-fields').show();
            toggle_advanced = true;
        }
    });

    /*xxxxxxxxxxxxxxxxxxx  change status xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx*/

    jQuery('.change-status').click(function () {
        return updateField(jQuery(this), 'status');
    });

    /*xxxxxxxxxxxxxxxxxxx  change type xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx*/

    jQuery('.change-type').click(function () {
        return updateField(jQuery(this), 'type_id');
    });

    /*xxxxxxxxxxxxxxxxxxx  change type xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx*/

    jQuery('.change-project').click(function () {
        return updateField(jQuery(this), 'project_id');
    });

    /*xxxxxxxxxxxxxxxxxxx  change type xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx*/

    jQuery('.change-milestone').click(function () {
        return updateField(jQuery(this), 'milestone_id');
    });

    /*xxxxxxxxxxxxxxxxxxx  change type xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx*/

    jQuery('.change-priority').click(function () {
        return updateField(jQuery(this), 'priority_id');
    });

    /*xxxxxxxxxxxxxxxxxxx  close task xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx*/

    jQuery('.close_open_task').click(function () {
        return closeOpenTask(jQuery(this));
    });

    /*xxxxxxxxxxxxxxxxxxx  close subtask xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx*/

    jQuery('.close_open_subtask').click(function () {
        return closeOpenSubtask(jQuery(this));
    });




    /*xxxxxxxxxxxxxxxxxxx  functions xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx*/


    function updateField(this_element, item) {

        var task_id = this_element.attr('task_id');
        var item_var = this_element.attr(item);
        var field_name = "form[" + item + "]";
        var label_str = this_element.attr('label');
        var task_element = this_element.closest('.task-setting');

        var data_object = {"form[id]": task_id};
        data_object["form[" + item + "]"] = item_var;

        kazist.addSpinningIcon(task_element);

        kazist.callAjaxByRoute('projects.tasks.save', data_object, true);
        task_element.find('.task-setting-label').text(label_str);
        task_element.removeClass('open');
        kazist.removeSpinningIcon(task_element);

        return false;
    }

    function closeOpenTask(this_element) {

        var task_id = this_element.attr('task_id');
        var closed = this_element.attr('closed');
        var label_str = this_element.attr('label');

        var data_object = {"form[id]": task_id, "form[closed]": closed};

        kazist.addSpinningIcon(this_element);

        kazist.callAjaxByRoute('projects.tasks.save', data_object, false);
        this_element.find('.close_open_label').text(label_str);

        if (this_element.hasClass('btn-danger')) {
            this_element.removeClass('btn-danger');
            this_element.addClass('btn-default');
        } else {
            this_element.removeClass('btn-default');
            this_element.addClass('btn-danger');
        }

        kazist.removeSpinningIcon(this_element);

        return false;
    }


    function closeOpenSubtask(this_element) {

        var subtask_id = this_element.attr('subtask_id');
        var closed = this_element.attr('closed');
        var label_str = this_element.attr('label');
        var return_url = kazist_document.current_url;
        
        var data_object = {"form[id]": subtask_id, "form[closed]": closed,"form[return_url]": btoa(return_url)};

        kazist.addSpinningIcon(this_element);

        kazist.callAjaxByRoute('projects.tasks.subtasks.save', data_object, false);
        this_element.find('.close_open_subtask_label').text(label_str);

        if (this_element.hasClass('btn-danger')) {
            this_element.removeClass('btn-danger');
            this_element.addClass('btn-default');
        } else {
            this_element.removeClass('btn-default');
            this_element.addClass('btn-danger');
        }

        kazist.removeSpinningIcon(this_element);

        return false;
    }

    function assignTask(this_element) {

        var task_id = jQuery('.task-single-form #id').val();
        var assignment = this_element.val();
        var assignment_name = this_element.attr('name');
        var li_element = this_element.closest('li');

        var data_object = {"form[id]": task_id};
        data_object[assignment_name] = assignment;

        kazist.addSpinningIcon(li_element);
        kazist.callAjaxByRoute('projects.tasks.save', data_object, false);
        kazist.removeSpinningIcon(li_element);

    }

    function submitForm() {
        jQuery('.task-single-form').submit();
    }
});
