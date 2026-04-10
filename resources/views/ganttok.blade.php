<!DOCTYPE html>

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <!-- <script src="https://docs.dhtmlx.com/gantt/codebase/dhtmlxgantt.js?v=6.0.0"></script>
    <link rel="stylesheet" href="https://docs.dhtmlx.com/gantt/codebase/dhtmlxgantt.css?v=6.0.0"> -->

    <script src="{{ asset ('assets/js/dhtmlxgantt.js') }}"></script>
    <link href="{{ asset ('assets/css/dhtmlxgantt.css') }}" type="text/css" rel="stylesheet" id="user-style-rtl">

<style>
  html, body {
    padding: 0px;
    margin: 0px;
    height: 100%;
  }

  #gantt_here {
    width:100%;
    height:100%;
  }

  .gantt_grid_scale .gantt_grid_head_cell,
  .gantt_task .gantt_task_scale .gantt_scale_cell {
    font-weight: bold;
    font-size: 14px;
    color: rgba(0, 0, 0, 0.7);
  }

  .resource_marker{
    text-align: center;
  }
  .resource_marker div{
    width: 28px;
    height: 28px;
    line-height: 29px;
    display: inline-block;
    border-radius: 15px;
    color: #FFF;
    margin: 3px;
  }
  .resource_marker.workday_ok div {
    background: #51c185;
  }

  .resource_marker.workday_over div{
    background: #ff8686;
  }

</style>

</head>

<body>

<!-- <input type=button value="Toggle resource chart" onclick=layout_change()> -->

<div id="gantt_here" style="width:100%; height:90%;"></div>

<script type="text/javascript">
// var show_grids = true;

// function layout_change() {
//     show_grids = !show_grids;
//     if (show_grids) gantt.config.layout = with_grids_layout;
//     else gantt.config.layout = without_grids_layout;
//     // gantt.render();
//     gantt.init("gantt_here");

// };

// var with_grids_layout = {
//     css: "gantt_container",
//     rows: [{
//             cols: [{
//                     view: "grid",
//                     group: "grids",
//                     scrollY: "scrollVer"
//                 },
//                 {
//                     resizer: true,
//                     width: 1
//                 },
//                 {
//                     view: "timeline",
//                     scrollX: "scrollHor",
//                     scrollY: "scrollVer"
//                 },
//                 {
//                     view: "scrollbar",
//                     id: "scrollVer",
//                     group: "vertical"
//                 }
//             ],
//             gravity: 2
//         },
//         {
//             resizer: true,
//             width: 1
//         },
//         {
//             view: "scrollbar",
//             id: "scrollHor"
//         }
//     ]
// };

// var without_grids_layout = {
//     css: "gantt_container",
//     rows: [{
//             cols: [{
//                     view: "timeline",
//                     scrollX: "scrollHor",
//                     scrollY: "scrollVer"
//                 },
//                 {
//                     view: "scrollbar",
//                     id: "scrollVer",
//                     group: "vertical"
//                 }
//             ],
//             gravity: 2
//         },
//         {
//             resizer: true,
//             width: 1
//         },
//         {
//             view: "scrollbar",
//             id: "scrollHor"
//         }
//     ]
// };

// gantt.config.columns = [{
//         name: "text",
//         tree: true,
//         width: 200,
//         resize: true
//     },
//     {
//         name: "start_date",
//         align: "center",
//         width: 80,
//         resize: true
//     },
    // {
    //     name: "owner",
    //     align: "center",
    //     width: 75,
    //     label: "Owner",
    //     template: function(task) {
    //         if (task.type == gantt.config.types.project) {
    //             return "";
    //         }

    //         var store = gantt.getDatastore("resource");
    //         var owner = store.getItem(task.owner_id);
    //         if (owner) {
    //             return owner.text;
    //         } else {
    //             return "Unassigned";
    //         }
    //     },
    //     resize: true
    // },
//     {
//         name: "duration",
//         width: 60,
//         align: "center"
//     },
//     {
//         name: "add",
//         width: 44
//     }
// ];

// var resourceConfig = {
//     columns: [{
//             name: "name",
//             label: "Name",
//             tree: true,
//             template: function(resource) {
//                 return resource.text;
//             }
//         },
//         {
//             name: "workload",
//             label: "Workload",
//             template: function(resource) {
//                 var tasks;
//                 var store = gantt.getDatastore(gantt.config.resource_store),
//                     field = gantt.config.resource_property;

//                 if (store.hasChild(resource.id)) {
//                     tasks = gantt.getTaskBy(field, store.getChildren(resource.id));
//                 } else {
//                     tasks = gantt.getTaskBy(field, resource.id);
//                 }

//                 var totalDuration = 0;
//                 for (var i = 0; i < tasks.length; i++) {
//                     totalDuration += tasks[i].duration;
//                 }

//                 return (totalDuration || 0) * 8 + "h";
//             }
//         }
//     ]
// };

// gantt.templates.resource_cell_class = function(start_date, end_date, resource, tasks) {
//     var css = [];
//     css.push("resource_marker");
//     if (tasks.length <= 1) {
//         css.push("workday_ok");
//     } else {
//         css.push("workday_over");
//     }
//     return css.join(" ");
// };

// gantt.templates.resource_cell_value = function(start_date, end_date, resource, tasks) {
//     return "<div>" + tasks.length * 8 + "</div>";
// };

// gantt.locale.labels.section_owner = "Owner";
// gantt.config.lightbox.sections = [{
//         name: "description",
//         height: 38,
//         map_to: "text",
//         type: "textarea",
//         focus: true
//     },
//     {
//         name: "owner",
//         height: 22,
//         map_to: "owner_id",
//         type: "select",
//         options: gantt.serverList("people")
//     },
//     {
//         name: "time",
//         type: "duration",
//         map_to: "auto"
//     }
// ];

// gantt.config.resource_store = "resource";
// gantt.config.resource_property = "owner_id";
// gantt.config.order_branch = true;
// gantt.config.open_tree_initially = true;
// gantt.config.layout = with_grids_layout;

// var resourcesStore = gantt.createDatastore({
//     name: gantt.config.resource_store,
//     type: "treeDatastore",
//     initItem: function(item) {
//         item.parent = item.parent || gantt.config.root_id;
//         item[gantt.config.resource_property] = item.parent;
//         item.open = true;
//         return item;
//     }
// });

// gantt.config.order_branch = true;
// gantt.config.order_branch_free = true;


// resourcesStore.attachEvent("onParse", function() {
//     var people = [];
//     resourcesStore.eachItem(function(res) {
//         if (!resourcesStore.hasChild(res.id)) {
//             var copy = gantt.copy(res);
//             copy.key = res.id;
//             copy.label = res.text;
//             people.push(copy);
//         }
//     });
//     gantt.updateCollection("people", people);
// });

// resourcesStore.parse([{
//         id: 1,
//         text: "QA",
//         parent: null
//     },
//     {
//         id: 2,
//         text: "Development",
//         parent: null
//     },
//     {
//         id: 3,
//         text: "Sales",
//         parent: null
//     },
//     {
//         id: 4,
//         text: "Other",
//         parent: null
//     },
//     {
//         id: 5,
//         text: "Unassigned",
//         parent: 4
//     },
//     {
//         id: 6,
//         text: "John",
//         parent: 1
//     },
//     {
//         id: 7,
//         text: "Mike",
//         parent: 2
//     },
//     {
//         id: 8,
//         text: "Anna",
//         parent: 2
//     },
//     {
//         id: 9,
//         text: "Bill",
//         parent: 3
//     },
//     {
//         id: 10,
//         text: "Floe",
//         parent: 3
//     }
// ]);
gantt.config.xml_date = "%Y-%m-%d %H:%i:%s";

gantt.init("gantt_here");

gantt.load("/api/data");
var dp = new gantt.dataProcessor("/api");

console.log(dp);
dp.init(gantt);
dp.setTransactionMode("REST");
// gantt.parse( < data > );
</script>
