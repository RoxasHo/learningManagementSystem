<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

    
    </head>
    <body >
      <form action="{{ route('test.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="certification">
    <input type="file" name="identityProof">
    <input type="file" name="picture">
    <button type="submit">Upload</button>
</form>

    </body>
</html>
