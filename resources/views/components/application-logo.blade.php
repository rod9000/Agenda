<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
    <defs>
        <linearGradient id="wing1" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#fbbf24"/>
            <stop offset="100%" stop-color="#fde68a"/>
        </linearGradient>
        <linearGradient id="wing2" x1="100%" y1="0%" x2="0%" y2="100%">
            <stop offset="0%" stop-color="#fb923c"/>
            <stop offset="100%" stop-color="#fed7aa"/>
        </linearGradient>
    </defs>
    <!-- Left wing -->
    <path d="M50 45 Q25 10 8 25 Q-5 38 15 55 Q25 62 50 50Z" fill="url(#wing1)" opacity="0.9"/>
    <!-- Left small wing -->
    <path d="M50 50 Q30 60 20 75 Q15 85 30 82 Q40 80 50 58Z" fill="url(#wing1)" opacity="0.7"/>
    <!-- Right wing -->
    <path d="M50 45 Q75 10 92 25 Q105 38 85 55 Q75 62 50 50Z" fill="url(#wing2)" opacity="0.9"/>
    <!-- Right small wing -->
    <path d="M50 50 Q70 60 80 75 Q85 85 70 82 Q60 80 50 58Z" fill="url(#wing2)" opacity="0.7"/>
    <!-- Body -->
    <ellipse cx="50" cy="48" rx="3" ry="18" fill="#d97706" opacity="0.8"/>
    <!-- Left antenna -->
    <path d="M48 32 Q40 18 35 10" stroke="#d97706" stroke-width="1.5" fill="none" stroke-linecap="round"/>
    <circle cx="35" cy="10" r="2" fill="#fbbf24"/>
    <!-- Right antenna -->
    <path d="M52 32 Q60 18 65 10" stroke="#ea580c" stroke-width="1.5" fill="none" stroke-linecap="round"/>
    <circle cx="65" cy="10" r="2" fill="#fb923c"/>
    <!-- Left wing dots -->
    <circle cx="28" cy="35" r="2.5" fill="white" opacity="0.6"/>
    <circle cx="20" cy="45" r="1.5" fill="white" opacity="0.5"/>
    <circle cx="35" cy="28" r="1.5" fill="white" opacity="0.4"/>
    <!-- Right wing dots -->
    <circle cx="72" cy="35" r="2.5" fill="white" opacity="0.6"/>
    <circle cx="80" cy="45" r="1.5" fill="white" opacity="0.5"/>
    <circle cx="65" cy="28" r="1.5" fill="white" opacity="0.4"/>
</svg>
