@extends('layouts.index')

@section('content')
    <div class="container-fluid vh-100 bg-dark">
        <div class="row h-100">
            <div class="col-12 d-flex justify-content-center align-items-center">
                <div class="">
                    <h1 class="text-white text-center text-uppercase">Battleship</h1>
                    <a class="btn btn-success w-100 mt-3" href="{{ route('game') }}" role="button">Play</a>
                    <a class="btn btn-warning w-100 mt-3" target="_blank"  href="https://email.app.bamboohr.com/c/eJyMUk1zmzAQ_TXoksEDQnzowCFxKsdunUnTGI9zYfSxNrIRoiCgzq_v4OaSW287-97b2fd2pTVmaLTkTtum1CqXgkREBdKPskj5YQjgU0GxjzFIFXBIKJdIWtPy5nrja1WmOIgTJIfeWZPDdRO87-NKNK-b9-U6WZ_ldfu4w2s96QOmTq7YALv6sj5b_fyxnp7fDvrHcjMc9mE993YrhsUqOxWGYn7dUKRySVIEeZjQMI1Jlmaoyo-BjEN-DCMpUp7QLIqlAgEURIxxeEyR_i8nHgl42y4EN8LaqltIa1CdV861vRfde5h5mE3TtKh4Lzo7wx5mc2a28TDTTe-6QToPswfuXA19pdvFyyNDXa74x8VePBKcDNf1bXAHEnTr5tRqeyohCnlwJECECJJUxEQRqRRPCOFxjLPAj1A_DaUD09bcwSxzYMosWSaJHC_jT7pfnc2-oOahff1efCXXVvIacmj83a-vyAhd_3nrEbryKS224_Qnyg6s2PymS8mesmOxZcjlL51VyOVvn0ovur-7d_3dt9kQctDwxv1b6lbdnuBvAAAA___BTMPs" role="button">Rules</a>
                </div>
            </div>
        </div>
    </div>
@endsection