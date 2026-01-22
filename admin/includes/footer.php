        </div>
    </main>
    <script>
        lucide.createIcons();

        // Mobile Sidebar Logic
        const mobileSidebar = document.getElementById('mobile-sidebar');
        const openBtn = document.getElementById('open-mobile-sidebar');
        const closeBtn = document.getElementById('close-mobile-sidebar');
        const backdrop = document.getElementById('mobile-sidebar-backdrop');
        const sidebarContent = document.getElementById('mobile-sidebar-content');

        if (openBtn) {
            openBtn.addEventListener('click', () => {
                mobileSidebar.classList.remove('hidden');
                setTimeout(() => {
                    sidebarContent.classList.remove('-translate-x-full');
                }, 10);
            });
        }

        const closeSidebar = () => {
            sidebarContent.classList.add('-translate-x-full');
            setTimeout(() => {
                mobileSidebar.classList.add('hidden');
            }, 300);
        };

        if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
        if (backdrop) backdrop.addEventListener('click', closeSidebar);
    </script>
</body>
</html>
