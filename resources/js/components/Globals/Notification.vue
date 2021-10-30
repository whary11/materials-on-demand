<template>
    <div class="toaster position-fixed bottom-0 end-0 p-3" style="z-index: 5">
        <div id="notifications-app"  class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
            <svg class="docs-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false">
                <rect width="100%" height="100%" fill="#007aff"></rect>
            </svg>
            <strong class="me-auto" id="notifications-title">
                Bootstrap
            </strong>
            <small>11 mins ago</small>
            <button class="btn-close" type="button" data-coreui-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="notifications-content">
                Hello, world! This is a toast message.
            </div>
        </div>
    </div>
</template>