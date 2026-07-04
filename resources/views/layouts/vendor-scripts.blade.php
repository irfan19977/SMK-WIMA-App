<!-- JAVASCRIPT -->



<script src="{{ URL::asset('build/libs/jquery/jquery.min.js') }}"></script>



<script src="{{ URL::asset('build/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>



<script src="{{ URL::asset('build/libs/metismenu/metisMenu.min.js') }}"></script>



<script src="{{ URL::asset('build/libs/simplebar/simplebar.min.js') }}"></script>



<script src="{{ URL::asset('build/libs/node-waves/waves.min.js') }}"></script>



<!-- Icon -->



<script src="https://unicons.iconscout.com/release/v2.0.1/script/monochrome/bundle.js"></script>

<!-- Livewire Scripts -->

@livewireScripts

<!-- Sidebar Menu Control Script -->
<script>
$(document).ready(function() {
    // Initialize MetisMenu
    $("#side-menu").metisMenu({
        toggle: true,
        preventDefault: false
    });
    
    // Function to control menu expansion based on active state
    function controlMenuExpansion() {
        // Get current URL path
        var currentPath = window.location.pathname;
        
        // First, collapse all sub-menus
        $('#side-menu .sub-menu').each(function() {
            $(this).hide();
            $(this).attr('aria-expanded', 'false');
            $(this).prev('a').removeClass('mm-active');
        });
        
        // Then, expand only menus that have active children
        $('#side-menu > li').each(function() {
            var $menuItem = $(this);
            var $submenu = $menuItem.find('> .sub-menu');
            var $hasArrow = $menuItem.find('> .has-arrow');
            
            if ($submenu.length > 0) {
                var hasActiveChild = false;
                
                // Check if any child link matches current path
                $submenu.find('a').each(function() {
                    var $link = $(this);
                    var href = $link.attr('href');
                    
                    if (href && href !== 'javascript: void(0);' && href !== '#') {
                        // Remove leading slash for comparison
                        var cleanHref = href.replace(/^\//, '');
                        var cleanCurrentPath = currentPath.replace(/^\//, '');
                        
                        // Check if current path matches this link
                        if (cleanCurrentPath.includes(cleanHref) || $link.hasClass('active')) {
                            hasActiveChild = true;
                            return false; // break the loop
                        }
                    }
                });
                
                // Also check if the main menu item itself is marked as active
                if ($hasArrow.hasClass('active')) {
                    hasActiveChild = true;
                }
                
                // Expand if has active child
                if (hasActiveChild) {
                    $submenu.show();
                    $submenu.attr('aria-expanded', 'true');
                    $hasArrow.addClass('mm-active');
                    
                    // Also expand parent menus recursively
                    var $parentSubmenu = $menuItem.closest('.sub-menu');
                    while ($parentSubmenu.length > 0) {
                        $parentSubmenu.show();
                        $parentSubmenu.attr('aria-expanded', 'true');
                        $parentSubmenu.prev('a').addClass('mm-active');
                        $parentSubmenu = $parentSubmenu.closest('.sub-menu');
                    }
                }
            }
        });
        
        // Handle nested sub-menus (second level)
        $('#side-menu .sub-menu .sub-menu').each(function() {
            var $nestedSubmenu = $(this);
            var $parentItem = $nestedSubmenu.parent('li');
            var $parentLink = $parentItem.children('a');
            
            if ($parentLink.hasClass('active')) {
                $nestedSubmenu.show();
                $nestedSubmenu.attr('aria-expanded', 'true');
                $parentLink.addClass('mm-active');
            }
        });
    }
    
    // Initial menu control
    controlMenuExpansion();
    
    // Re-control menu after Livewire updates
    if (window.Livewire) {
        window.Livewire.hook('message.processed', () => {
            setTimeout(controlMenuExpansion, 100);
        });
    }
    
    // Override metisMenu click behavior to ensure proper expansion
    $('#side-menu .has-arrow').off('click').on('click', function(e) {
        e.preventDefault();
        
        var $this = $(this);
        var $submenu = $this.next('.sub-menu');
        var $parentLi = $this.parent('li');
        
        // Toggle current submenu
        if ($submenu.is(':visible')) {
            $submenu.slideUp(200, function() {
                $submenu.attr('aria-expanded', 'false');
                $this.removeClass('mm-active');
            });
        } else {
            // Close other submenus at the same level (not nested ones)
            $parentLi.siblings().find('> .sub-menu').each(function() {
                $(this).slideUp(200, function() {
                    $(this).attr('aria-expanded', 'false');
                    $(this).prev('.has-arrow').removeClass('mm-active');
                });
            });
            
            // Open current submenu
            $submenu.slideDown(200, function() {
                $submenu.attr('aria-expanded', 'true');
                $this.addClass('mm-active');
            });
        }
        
        return false;
    });
});
</script>

@yield('scripts')