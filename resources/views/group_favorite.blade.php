@extends('layouts.groups')


@section('content')


<div class="bg-paper py-4">
    <div class="container" style="max-width: 600px">
        <!-- if(!$group[user]) -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(!empty($group_user_phrases))

            <div class="card-body">
                <div class="form-group form-control-lg my-3">
                    @foreach($favorite_phrases as $favorite_phrase)
                        <div class="d-flex justify-content-center">
                            <div class="text-center flex-grow-1">
                                <p class="mt-4" style="font-size:20px">{{$favorite_phrase['japanese']}}</p>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <p class="text-center" style="font-size:20px">{{$favorite_phrase['phrase']}}</p>
                        </div>
                        <div class="row justify-content-center">
                            <p class="text-center" style="font-size:20px" >{{$favorite_phrase['memo']}}</p>
                        </div>
                        <div class="border-top">
                    @endforeach
                </div>
            </div>  

        @else
            <div class="card-body">
                <div class="form-group form-control-lg my-3">
                    お気に入り登録されたフレーズはまだありません。
                </div>
            </div>  
        @endif
    </div>
</div>

@endsection

@section('javascript')

<script>

</script>

@endsection

