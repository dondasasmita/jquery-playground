<!-- Sample Codes to work with ajax call with tables passing different input values -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Client Form</title>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

    <style>
        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 3px solid #ddd;
        }

        .column {
            float: left;
            width: 33.33%;
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        .button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 8px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 10px;
        }

        input[type=text] {
            width: 80%;
            padding: 12px 20px;
            margin: 8px 0;
            box-sizing: border-box;
        }
    </style>
</head>

<body>
    <h1>Clients</h1>
    <div>
        <h2>Client Secret Form</h2>
    </div>
    <div>

        <div>
            <form action="#" method="get">
                <div class="row">
                    <div class="column">
                        <label for="client_id">Client ID</label>
                        <br><br>
                        <input id="client_id" type="text" name="client_id" required>
                        <br><br>
                    </div>
                    <div class="column">
                        <label for="client_id">Client Website</label>
                        <br><br>
                        <input width="100" id="website" type="text" name="website" required>
                    </div>
                </div>
                <div class="row">
                    <button class="button" id="issue">Issue Secret</button>
                </div>
            </form>
        </div>
        <br><br><br>

        <div class="result">
            <h5>Share this with client</h5>
            <h4>Client ID</h4>
            <input id="client_id_result" type="text" value="" disabled>
            <h4>Client Secret</h4>
            <input id="client_secret_result" type="text" value="" disabled>
        </div>

        <br><br><br>

        <div id="client_lists_wrapper">
            <table id="client_lists" style="width: 80%" frame=void rules=rows align="center">
                <tr>
                    <th align="left">Client ID</th>
                    <th align="left">Client Secret</th>
                    <th align="left">Client Website</th>
                    <th align="left">Action</th>
                </tr>
                <?php $i = 1; ?>
                <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?php echo $client["client_id"] ?>
                    </td>
                    <td><?php echo $client["client_secret"] ?>
                    </td>
                    <td><?php echo $client["website"] ?>
                    </td>
                    <td>
                        <div class="column">
                            <form action="#" method="get">
                                <input id="client_<?php echo $i?>"
                                    type="text"
                                    value="<?php echo $client["client_id"]?>"
                                    hidden>
                                <button class="button reissue"
                                    id="reissue_<?php echo $i?>">Reset
                                    Secret</button>
                            </form>
                        </div>
                    </td>
                    <?php $i++; ?>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>



</body>
<script>
    $(document).ready(() => {

        function resizeInput() {
            $(this).attr('size', $(this).val().length);
        }

        $('.result').hide();
        $('#issue').click((e) => {
            e.preventDefault();
            let data = {
                client_id: $("#client_id").val(),
                redirect_uri: $("#redirect_uri").val(),
                action: "issue_token"
            }
            if (data.client_id != "") {
                $.get("ajax.php", data).done((data) => {
                    $("#client_id").val("");
                    $("#redirect_uri").val("");
                    result = JSON.parse(data);
                    $('.result').show();
                    $('#client_id_result').val(result.client_id);
                    $('#client_secret_result').val(result.client_secret);
                    $('#client_secret_result').keyup(resizeInput).each(resizeInput);
                    url = "www.website.com";

                    // this will load the table
                    $("#client_lists_wrapper").load(url + " #client_lists");
                })
            } else {
                // error
            }
        })

        $('.reissue').click(function(e) {
            e.preventDefault();

            /**
             * Codes that does the trick 
             */

            // get the id 
            let id = this.id;

            // get the client id from the form
            let client_id = id.replace("reissue", "client");
            let data = {
                action: "reset_secret",
                client_id: $("#" + client_id).val()
            }
            $.get("ajax.php", data).done(() => {

                // this will load the whole document
                location.reload();
            })
        })
    })
</script>

</html>