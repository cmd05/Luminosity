DarkReader.setFetchMethod(window.fetch);

let theme = 'light';

if (!localStorage.getItem("theme")) localStorage.setItem('theme', 'light');

if (localStorage.getItem('theme') === 'dark') {
    document.querySelectorAll('.toggle-theme').forEach(btn => btn.querySelector('input').checked = true);
    darkTheme();
}

function darkTheme() {
    DarkReader.enable({
        brightness: 100,
        contrast: 95,
        sepia: 10
    });
    localStorage.setItem('theme', 'dark');
}

function lightTheme() {
    DarkReader.disable();
    localStorage.setItem('theme', 'light');
}

document.querySelectorAll('.toggle-theme').forEach(btn => {
    btn.addEventListener('input', function() {
        if (theme === 'light') {
            theme = 'dark';
            darkTheme();
        } else {
            theme = 'light';
            lightTheme();
        }

        document.querySelectorAll('.toggle-theme').forEach(btn => btn.querySelector('input').checked = theme !== 'light');
    })
})