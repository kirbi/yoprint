
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Upload Millions Records Laravel - CodeCeef</title>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0"  id="reload">
            <div class="container">
                <div class="container-fluid border">
                    <div class="row">
                        <div class="col-12">
                            <div class="bordered">
                                <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                    <div class="d-flex justify-content-center">
                                        <input type="file" name="csv">
                                            <input type="submit" value="submit">
                                    </div>
                                        
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="container-fluid  border">
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
