import DataTable from 'datatables.net-bs4';
import 'datatables.net-responsive-bs4';
import 'datatables.net-buttons-bs4';
import 'datatables.net-select-bs4';
import 'datatables.net-searchbuilder-bs4';

import JSZip from 'jszip'; // For Excel export
import PDFMake from 'pdfmake'; // For PDF export
import vfs from "./fonts/vfs_fonts";
PDFMake.vfs = vfs;
PDFMake.fonts = {
    Roboto: {
        normal: 'Roboto-Regular.ttf',
        bold: 'Roboto-Medium.ttf',
        italics: 'Roboto-Italic.ttf',
        bolditalics: 'Roboto-MediumItalic.ttf'
    }
};

import 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-buttons/js/buttons.print.mjs';
import 'datatables.net-buttons/js/buttons.colVis.mjs';

DataTable.Buttons.jszip(JSZip);
DataTable.Buttons.pdfMake(PDFMake);

let table = new DataTable('#dataTable', {
    responsive: true,
    select: true,
    colReorder: true,
    stateSave: true,
    dom: 'QBfrtip',
    pageLength: 25,
    language: {
        url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/nl-NL.json',
    },
    buttons: [{
        extend: 'print',
        orientation: 'landscape',
        exportOptions: {
            columns: [':visible', function (idx, data, node) {
                if (node.classList.contains('no-print')) return false
                if (node.classList.contains('dtr-hidden')) return false
                return true
            }]
        }
    },
    {
        extend: 'excel',
        exportOptions: {
            columns: [function (idx, data, node) {
                if (node.classList.contains('no-print')) return false
                return true
            }, ':visible']
        }
    },
    {
        extend: 'pdfHtml5',
        orientation: 'landscape',
        pageSize: 'A4',
        exportOptions: {
            columns: function (idx, data, node) {
                if (node.classList.contains('no-print')) return false
                if (node.classList.contains('dtr-hidden')) return false
                return true
            }
        }
    }]
})