import mix from 'laravel-mix';
import tailwindcss from 'tailwindcss';
import autoprefixer from 'autoprefixer';

mix.js('resources/js/app.js', 'public/js')
   .postCss('resources/css/app.css', 'public/css', [
       tailwindcss,
       autoprefixer,
   ]); 