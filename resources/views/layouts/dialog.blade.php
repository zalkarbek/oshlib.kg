@push('css_lib')
    <style>
        dialog.bdrp::backdrop {
            background: repeating-linear-gradient(
                60deg,
                rgba(0, 0, 0, 0.2),
                rgba(0, 0, 0, 0.2) 1px,
                rgba(0, 0, 0, 0.3) 1px,
                rgba(0, 0, 0, 0.3) 20px
            );
            backdrop-filter: blur(1px);
        }

        .bcgrnd {
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        @keyframes animate-top {
            from {
                top:-300px;
                opacity:0
            }
            to {
                top:0;
                opacity:1
            }
        }

        .animate {
            animation-name: animate-top;
            animation-duration: 0.4s;
        }

        .modal-content {
            position: relative;
            background-color: #fefefe;
            margin: auto;
            padding: 0;
            border: 1px solid #888;
            width: 80%;
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);

        }

        .modal-header {
            padding: 2px 16px;
            background-color: #000;
            color: #FFF;
        }
    </style>
@endpush
<dialog id="@yield('dialog-id')" class="bcgrnd animate">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close">&times;</span>
            <h2>This is a Header</h2>
        </div>
        <div class="modal-body">
            @yield('dialog-content')
        </div>
    </div>
</dialog>
