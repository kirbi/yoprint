
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Upload Millions Records Laravel - CodeCeef</title>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0"  id="reload">

            <div class="row">
                <div class="col-12">
                    <div class="bordered">
                        <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                                <input type="file" name="csv">
                                <input type="submit" value="submit">
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <table class="table table-border">
                    <thead>
                        <th>No</th>
                        <th>File name</th>
                        <th>Time Upload</th>
                        <th>Status</th>
                    </thead>
                    <tbody>
                        <?php $i =1; foreach($data as $d){
                            echo '<tr>';
                            echo '<td>'.$i.'</td>';
                            echo '<td>'.$d->name_file.'</td>';
                            echo '<td>'.$d->time_uploaded.'</td>';
                            echo '<td>'.$d->status.'</td>';

                            echo '</tr>';
                        } ?>
                    </tbody>
                </table>
            </div>
            

        </div>
    </body>
    <script>
            function loadlink(){
                $('#reload').load('/upload');
                console.log('TESTING!!!!');
            }

            
            setInterval(function(){
                loadlink();
            },  60 * 1000);
    </script>
</html>
