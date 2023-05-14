
@extends('layouts.register')


@section('content')

<div class="bg-paper py-4">
    <div class="container" style="max-width: 540px">
        <h3 class="text-center">
            招待メール送信
        </h3>
        <p style="font-size: 14px;">
            フレーズの共有するために招待したい、ユーザーのメールアドレスを入力してください。<br>
            入力したメールアドレス宛てに、招待用のユーザー登録ページの案内をお送りします。
        </p>

        @if (Auth::id() === config('const.GUEST_USER_ID'))
        <p class="text-danger">
            ※ゲストユーザーは、招待メールを送信できません。
        </p>
        @endif

        <div class="card my-4 shadow-sm">
            <div class="card-body">

                @if (session('status'))
                <div class="card-text alert alert-success">
                    {{ session('status') }}
                </div>
                @endif

                
                <form method="POST" action="{{ route('invitation_confirm') }}">
                    @csrf
                    <div class="form-group">
                        <!-- グループidも送る -->
                        <label for="email">メールアドレス</label>
                        <input class="form-control" type="email" id="email" name="email" required
                            placeholder="メールアドレスを入力" value="{{ old('email') }}">
                        <p class="text-muted small ml-1 mb-0">※招待したいユーザーのメールアドレスを入力してください。</p>
                        <p class="text-muted small ml-1">※メール送信後、24時間以内に登録してください。</p>
                    </div>
                    <input type="hidden" name="group_id" value="{{$group_id}}">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary mt-4">
                            <b>送信する</b>
                        </button>
                        <button type="button" onClick="history.back()" class="btn btn-block border">
                            <i class="fas fa-arrow-left mr-1"></i>戻る
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection