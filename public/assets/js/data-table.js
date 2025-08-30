// npm package: datatables.net-bs5
// github link: https://github.com/DataTables/Dist-DataTables-Bootstrap5

$(function() {
  'use strict';

  $(function() {
    $('#dataTableExample').DataTable({
        columnDefs: [{
            "defaultContent": "-",
            "targets": "_all"
        }],
      "aLengthMenu": [
        [10, 30, 50, -1],
        [10, 30, 50, "All"]
      ],
      "iDisplayLength": 10,
      "language": {
        search: ""
      }
    });
    $('#dataTableExample').each(function() {

      var datatable = $(this);
      // SEARCH - Add the placeholder for Search and Turn this into in-line form control
      var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
      search_input.attr('placeholder', 'Search');
      search_input.removeClass('form-control-sm');
      // LENGTH - Inline-Form control
      var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
      length_sel.removeClass('form-control-sm');
    });
  });

});
/////////////////////////id="dataTableExample1"///////

$(function() {
  'use strict';
  $(function() {
    $('#dataTableExample1').DataTable({
        columnDefs: [{
            "defaultContent": "-",
            "targets": "_all"
        }],
      "aLengthMenu": [
        [10, 30, 50, -1],
        [10, 30, 50, "All"]
      ],
      "iDisplayLength": 10,
      "language": {
        search: ""
      }
    });
    $('#dataTableExample1').each(function() {
      var datatable = $(this);
      // SEARCH - Add the placeholder for Search and Turn this into in-line form control
      var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
      search_input.attr('placeholder', 'Search');
      search_input.removeClass('form-control-sm');
      // LENGTH - Inline-Form control
      var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
      length_sel.removeClass('form-control-sm');
    });
  });

});
//////////////////id="dataTableExample2"/////////
$(function() {
    'use strict';
    $(function() {
      $('#dataTableExample2').DataTable({
        columnDefs: [{
            "defaultContent": "-",
            "targets": "_all"
        }],
        "aLengthMenu": [
          [10, 30, 50, -1],
          [10, 30, 50, "All"]
        ],
        "iDisplayLength": 10,
        "language": {
          search: ""
        }
      });
      $('#dataTableExample2').each(function() {
        var datatable = $(this);
        // SEARCH - Add the placeholder for Search and Turn this into in-line form control
        var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
        search_input.attr('placeholder', 'Search');
        search_input.removeClass('form-control-sm');
        // LENGTH - Inline-Form control
        var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
        length_sel.removeClass('form-control-sm');
      });
    });

  });
//////////////////id="dataTableExample4"/////////
  $(function() {
    'use strict';
    $(function() {
      $('#dataTableExample4').DataTable({
        columnDefs: [{
            "defaultContent": "-",
            "targets": "_all"
        }],
        "aLengthMenu": [
          [10, 30, 50, -1],
          [10, 30, 50, "All"]
        ],
        "iDisplayLength": 10,
        "language": {
          search: ""
        }
      });
      $('#dataTableExample4').each(function() {
        var datatable = $(this);
        // SEARCH - Add the placeholder for Search and Turn this into in-line form control
        var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
        search_input.attr('placeholder', 'Search');
        search_input.removeClass('form-control-sm');
        // LENGTH - Inline-Form control
        var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
        length_sel.removeClass('form-control-sm');
      });
    });

  });


