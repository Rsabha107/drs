<!DOCTYPE html>

<head>
    <!-- <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"> -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- <script src="https://docs.dhtmlx.com/gantt/codebase/dhtmlxgantt.js?v=6.0.0"></script>
    <link rel="stylesheet" href="https://docs.dhtmlx.com/gantt/codebase/dhtmlxgantt.css?v=6.0.0"> -->

    <script src="{{ asset ('assets/js/dhtmlxgantt.js?v=8.0.6') }}"></script>
    <link href="{{ asset ('assets/css/dhtmlxgantt.css?v=8.0.6') }}" type="text/css" rel="stylesheet" id="user-style-rtl">
    <link href="{{ asset ('assets/css/controls_styles.css?v=8.0.6') }}" type="text/css" rel="stylesheet" id="user-style-rtl">


    <style type="text/css">
        html,
        body {
            height: 90%;
            /* height:100%; */
            padding: 0px;
            margin: 0px;
            /* margin: unset; */
            /* overflow: hidden; */
        }

        .status_line {
            background-color: #0ca30a;
        }

        #gantt_here {
            width: 90%;
            height: 90%;
        }

        .gantt_grid_scale .gantt_grid_head_cell,
        .gantt_task .gantt_task_scale .gantt_scale_cell {
            font-weight: bold;
            font-size: 14px;
            color: rgba(0, 0, 0, 0.7);
        }

        .resource_marker {
            text-align: center;
        }

        .resource_marker div {
            width: 28px;
            height: 28px;
            line-height: 29px;
            display: inline-block;
            border-radius: 15px;
            color: #FFF;
            margin: 3px;
        }

        .gantt_task_line.gantt_task_inline_color .gantt_task_progress {
            background-color: rgb(54, 54, 54);
            opacity: 0.2;
        }

        .gantt_task_progress {
            text-align: left;
            padding-left: 10px;
            box-sizing: border-box;
            color: white;
            font-weight: bold;
        }

        .resource_marker.workday_ok div {
            background: #51c185;
        }

        .resource_marker.workday_over div {
            background: #ff8686;
        }

        .weekend {
            background: #f4f7f4 !important;
        }
    </style>
</head>

<body>
    <form  action="{{ route ('main.sec.adminuser.mupdate') }}" method="post">
    @csrf
    <input type="hidden" name="user_id" value="10">
               <input type="radio" id="scale5" class="gantt_radio" name="scale" value="year">

                        <input name="roles" type="number" placeholder="role id" required>

               <button type="submit" >Assign 10</button>

    </form>

</body>
