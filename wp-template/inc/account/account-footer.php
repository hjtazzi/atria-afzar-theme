    <div id="app_root" class="root"></div>

    <div id="svg_root">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="0" height="0" preserveAspectRatio="xMidYMid" viewBox="0 0 280 50">
            <defs>
                <g id="svg-live-bg">
                    <path class="svg-live-bg-l1" d="M 0 0 C 160 0 220 40 280 40 L 280 0" fill="#fdfdff" opacity="0.75">
                        <animate attributeName="d" dur="8s" repeatCount="indefinite" begin="0" values="M 0 0 C 160 0 220 40 280 40 L 280 0; M 0 0 C 80 20 110 10 280 20 L 280 0; M 0 0 C 160 0 220 40 280 40 L 280 0;">
                        </animate>
                    </path>
                    <path class="svg-live-bg-l2" d="M 0 0 C 140 30 200 40 280 20 L 280 0" fill="#fdfdff" opacity="0.75">
                        <animate attributeName="d" dur="8s" repeatCount="indefinite" begin="-0.5s" values="M 0 0 C 140 30 200 40 280 20 L 280 0; M 0 0 C 160 0 220 40 280 40 L 280 0; M 0 0 C 140 30 200 40 280 20 L 280 0;">
                        </animate>
                    </path>
                    <path class="svg-live-bg-l3" d="M 60 0 C 170 50 220 50 280 30 L 280 0" fill="#fdfdff" opacity="0.75">
                        <animate attributeName="d" dur="8s" repeatCount="indefinite" begin="-0.75s" values="M 60 0 C 170 50 220 50 280 30 L 280 0; M 0 0 C 160 0 220 40 280 40 L 280 0; M 60 0 C 170 50 220 50 280 30 L 280 0;">
                        </animate>
                    </path>
                </g>
            </defs>
        </svg>
    </div>

<?php wp_footer(); ?>
</body>

</html>