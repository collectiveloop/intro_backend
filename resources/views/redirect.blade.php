@extends('layouts.default')
@section('content')
<script type='text/javascript'>
  document.location="introapp://<?php echo $url;?><?php echo $token;?>";
</script>

@endsection
