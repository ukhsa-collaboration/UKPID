<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabbed Interface</title>
    <style>
        .column {
            float: left;
        }
        .section, .field-row, .form-table, fieldset {
            margin-bottom: 20px;
        }
        .field-row, fieldset {
            display: flex;
            flex-wrap: wrap;
        }
        .field, .field-inner {
            box-sizing: border-box;
            padding: 5px;
            margin-bottom: 10px;
        }
        .field > label, label, fieldset > label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        .checkbox, .radio, .checkbox-label, .radio-label {
            margin-bottom: 5px;
        }
        .checkbox-label, .radio-label {
            font-weight: normal !important;
            display: inline-flex;
            align-items: center;
            margin-right: 10px;
        }
        input, select, textarea {
            width: 100%;
            padding: 0 8px;
            box-sizing: border-box;
            height: 30px;
            vertical-align: middle;
        }
        input[type="checkbox"], input[type="radio"] {
            width: auto;
            margin-right: 5px;
            margin-top: 0;
        }
        .clearfix::after, .json-wrapper {
            content: "";
            clear: both;
            display: table;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .tab {
            cursor: pointer;
            padding: 10px;
            border: 1px solid grey;
            display: inline-block;
            margin-right: -1px;
        }
        .active-tab {
            background-color: #ddd;
            border-bottom: none;
        }
        .form-table {
            width: 100%;
            border-collapse: collapse;
        }
        .form-table th, .form-table td {
            border: 1px solid grey;
            text-align: left;
        }
        .form-table th {
            font-weight: bold;
            border: none;
        }
        .form-table td {
            border: 1px solid grey;
        }
        fieldset {
            border: none;
            padding: 0;
            margin: 0 0 20px;
        }
        legend {
            font-weight: bold;
            margin-bottom: 10px;
        }

    </style>

</head>
<body>

<div id="tabs"></div>
<div id="tab-content"></div>

<script>
    document.addEventListener('DOMContentLoaded', () => fetchDataAndRender('/api/form-data'));

    async function fetchDataAndRender(url) {
        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error('Network response was not ok');
            const data = await response.json();
            renderTabs(data);
            renderContent(data);
            activateFirstTab();
        } catch (error) {
            console.error('Fetch operation error:', error);
        }
    }

    function renderTabs(data) {
        const tabsContainer = document.getElementById('tabs');
        tabsContainer.innerHTML = Object.keys(data)
            .map(tabName => `<div class="tab" onclick="changeTab('${tabName}')" id="tab-${tabName}">${tabName}</div>`)
            .join('');
    }

    function renderContent(data) {
        const contentContainer = document.getElementById('tab-content');
        contentContainer.innerHTML = Object.entries(data)
            .map(([tabName, tabData]) => `<div class="tab-content" id="content-${tabName}">${getTabHTML(tabData)}<div class="json-wrapper"><pre>${JSON.stringify(tabData, null, 2)}</pre></div></div>`)
            .join('');
    }

    function activateFirstTab() {
        const firstTabName = document.querySelector('.tab')?.id;
        if (firstTabName) changeTab(firstTabName.replace('tab-', ''));
    }

    function getTabHTML(tabData) {
        return tabData.map(column => `<div class="column" style="width: ${column.width || 100}%;">${getColumnHTML(column)}</div>`).join('');
    }

    function getColumnHTML(column) {
        return column.sections.map(section => `<div class="section"><h2>${section.title}</h2>${getSectionFieldsHTML(section)}</div>`).join('');
    }

    function getSectionFieldsHTML(section) {
        let fieldsHtml = section.fields.reduce((acc, field) => {
            let fieldHTML = "";

            if (field.type === 'fieldset') {
                const legend = field.fieldsetLegend ? `<legend>${field.fieldsetLegend}</legend>` : '';
                const fieldsetFieldsHTML = field.fields.map(f => getFieldHTML(f, true)).join('');
                fieldHTML = `<fieldset class="field" style="width: ${field.width || 100}%;">${legend}${fieldsetFieldsHTML}</fieldset>`;
            } else if (field.type === 'space') {
            } else {
                fieldHTML = getFieldHTML(field);
            }

            return acc + `<div class="field" style="width: ${field.width || 100}%;">${fieldHTML}</div>`;
        }, '');

        return `<div class="field-row">${fieldsHtml}</div>`;
    }

    function getFieldHTML(field) {
        let fieldHTML = '';

        // Determine if the label should be hidden
        const labelStyle = field.nameHidden ? ' style="display: none;"' : '';

        if (field.type === 'space') {
            fieldHTML = `<div class="field space" style="width: ${field.width || 100}%;"></div>`;
        } else if (field.type === 'checkbox' || field.type === 'radio') {
            fieldHTML = getSpecialFieldHTML(field, field.nameHidden);
        } else if (field.type === 'dropdown') {
            fieldHTML = getDropdownHTML(field, field.nameHidden);
        } else if (field.type === 'form') {
            fieldHTML = generateFormGrid(field);
        } else if (field.type === 'textarea') {
            fieldHTML = `<label${labelStyle}>${field.name}</label><textarea id="${field.field}" name="${field.field}" style="width: 100%; height: 100%;"></textarea>`;
        } else {
            fieldHTML = `<label${labelStyle}>${field.name}</label><input type="${field.type || 'text'}" id="${field.field}" name="${field.field}" />`;
        }

        return fieldHTML;
    }


    function getSpecialFieldHTML(field, nameHidden) {
        const labelStyle = nameHidden ? ' style="display: none;"' : '';
        const fieldLabels = field.values.map((value, index) =>
            `<label class="${field.type}-label"${labelStyle}><input type="${field.type}" id="${field.field}_${index}" name="${field.field}" value="${value}" />${value}</label>`
        ).join('');

        return `<label${labelStyle} style="font-weight: bold;">${field.name}</label>` + fieldLabels;
    }


    function getDropdownHTML(field, nameHidden) {
        const labelStyle = nameHidden ? ' style="display: none;"' : '';
        const options = field.values.map(value => `<option value="${value}">${value}</option>`).join('');

        return `<label${labelStyle} style="font-weight: bold;">${field.name}</label><select id="${field.field}" name="${field.field}">${options}</select>`;
    }


    function generateFormGrid(formField) {
        const tableHeaders = formField.cells.map(cell =>
            `<th style="width: ${cell.width || 100}%;">${cell.name}</th>`
        ).join('');

        const tableRows = Array.from({ length: formField.defaultRows }, () =>
            `<tr>${formField.cells.map(cell =>
                `<td style="width: ${cell.width || 100}%;"><input type="text" name="${cell.field}" style="width: 100%; border: none; box-sizing: border-box;"></td>`
            ).join('')}</tr>`
        ).join('');

        return `<table class="form-table" style="width: 100%; margin: 20px 0;"><tr>${tableHeaders}</tr>${tableRows}</table>`;
    }


    function changeTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
        document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active-tab'));
        document.getElementById(`content-${tabName}`).classList.add('active');
        document.getElementById(`tab-${tabName}`).classList.add('active-tab');
    }
</script>

</body>
</html>

</script>

</body>
</html>
