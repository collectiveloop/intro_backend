@extends('layouts.default')
@section('content')
<script type='text/javascript'>
  document.location="introapp://forgot-password/<?php echo url('forgot-password/'.$token);?>";
</script>

@endsection
