/**
 * Frontend JavaScript - Multi-Step Form
 * 
 * @package Mida
 */

(function($) {
    'use strict';

    // ==================== APARTMENT DATA ====================
    // Sample apartment data (based on searchfile.txt structure)
    const apartmentsData = [
        // Building 3, Entrance 9 - 9 floors
        {id: 1, building: 3, entrance: 9, floor: 1, floors: 9, apartment: 1, rooms: 3, area: '72.90 - 85.20', price: 87480},
        {id: 2, building: 3, entrance: 9, floor: 2, floors: 9, apartment: 1, rooms: 3, area: '72.90 - 85.20', price: 87480},
        {id: 3, building: 3, entrance: 9, floor: 3, floors: 9, apartment: 1, rooms: 3, area: '72.90 - 85.20', price: 87480},
        {id: 4, building: 3, entrance: 9, floor: 4, floors: 9, apartment: 1, rooms: 3, area: '72.90 - 85.20', price: 87480},
        {id: 5, building: 3, entrance: 9, floor: 5, floors: 9, apartment: 1, rooms: 3, area: '72.90 - 85.20', price: 87480},
        {id: 6, building: 3, entrance: 9, floor: 6, floors: 9, apartment: 1, rooms: 3, area: '72.90 - 85.20', price: 87480},
        {id: 7, building: 3, entrance: 9, floor: 7, floors: 9, apartment: 1, rooms: 3, area: '72.90 - 85.20', price: 87480},
        {id: 8, building: 3, entrance: 9, floor: 8, floors: 9, apartment: 1, rooms: 3, area: '72.90 - 85.20', price: 87480},
        {id: 9, building: 3, entrance: 9, floor: 9, floors: 9, apartment: 1, rooms: 3, area: '72.90 - 85.20', price: 87480},
        
        // Building 3, Entrance 11 - 9 floors  
        {id: 10, building: 3, entrance: 11, floor: 1, floors: 9, apartment: 1, rooms: 2, area: '57.70 - 69.10', price: 69240},
        {id: 11, building: 3, entrance: 11, floor: 2, floors: 9, apartment: 1, rooms: 2, area: '57.70 - 69.10', price: 69240},
        {id: 12, building: 3, entrance: 11, floor: 3, floors: 9, apartment: 1, rooms: 2, area: '57.70 - 69.10', price: 69240},
        {id: 13, building: 3, entrance: 11, floor: 4, floors: 9, apartment: 1, rooms: 2, area: '57.70 - 69.10', price: 69240},
        {id: 14, building: 3, entrance: 11, floor: 5, floors: 9, apartment: 1, rooms: 2, area: '57.70 - 69.10', price: 69240},
        {id: 15, building: 3, entrance: 11, floor: 6, floors: 9, apartment: 1, rooms: 2, area: '57.70 - 69.10', price: 69240},
        {id: 16, building: 3, entrance: 11, floor: 7, floors: 9, apartment: 1, rooms: 2, area: '57.70 - 69.10', price: 69240},
        {id: 17, building: 3, entrance: 11, floor: 8, floors: 9, apartment: 1, rooms: 2, area: '57.70 - 69.10', price: 69240},
        {id: 18, building: 3, entrance: 11, floor: 9, floors: 9, apartment: 1, rooms: 2, area: '57.70 - 69.10', price: 69240},
        
        // Building 4, Entrance 13 - 9 floors
        {id: 19, building: 4, entrance: 13, floor: 1, floors: 9, apartment: 1, rooms: 1, area: '40.10 - 50.80', price: 48120},
        {id: 20, building: 4, entrance: 13, floor: 2, floors: 9, apartment: 1, rooms: 1, area: '40.10 - 50.80', price: 48120},
        {id: 21, building: 4, entrance: 13, floor: 3, floors: 9, apartment: 1, rooms: 1, area: '40.10 - 50.80', price: 48120},
        {id: 22, building: 4, entrance: 13, floor: 4, floors: 9, apartment: 1, rooms: 1, area: '40.10 - 50.80', price: 48120},
        {id: 23, building: 4, entrance: 13, floor: 5, floors: 9, apartment: 1, rooms: 1, area: '40.10 - 50.80', price: 48120},
        {id: 24, building: 4, entrance: 13, floor: 6, floors: 9, apartment: 1, rooms: 1, area: '40.10 - 50.80', price: 48120},
        {id: 25, building: 4, entrance: 13, floor: 7, floors: 9, apartment: 1, rooms: 1, area: '40.10 - 50.80', price: 48120},
        {id: 26, building: 4, entrance: 13, floor: 8, floors: 9, apartment: 1, rooms: 1, area: '40.10 - 50.80', price: 48120},
        {id: 27, building: 4, entrance: 13, floor: 9, floors: 9, apartment: 1, rooms: 1, area: '40.10 - 50.80', price: 48120},
        
        // Building 4, Entrance 15 - 9 floors
        {id: 28, building: 4, entrance: 15, floor: 1, floors: 9, apartment: 2, rooms: 4, area: '99 - 119.50', price: 118800},
        {id: 29, building: 4, entrance: 15, floor: 2, floors: 9, apartment: 2, rooms: 4, area: '99 - 119.50', price: 118800},
        {id: 30, building: 4, entrance: 15, floor: 3, floors: 9, apartment: 2, rooms: 4, area: '99 - 119.50', price: 118800},
        {id: 31, building: 4, entrance: 15, floor: 4, floors: 9, apartment: 2, rooms: 4, area: '99 - 119.50', price: 118800},
        {id: 32, building: 4, entrance: 15, floor: 5, floors: 9, apartment: 2, rooms: 4, area: '99 - 119.50', price: 118800},
        {id: 33, building: 4, entrance: 15, floor: 6, floors: 9, apartment: 2, rooms: 4, area: '99 - 119.50', price: 118800},
        {id: 34, building: 4, entrance: 15, floor: 7, floors: 9, apartment: 2, rooms: 4, area: '99 - 119.50', price: 118800},
        {id: 35, building: 4, entrance: 15, floor: 8, floors: 9, apartment: 2, rooms: 4, area: '99 - 119.50', price: 118800},
        {id: 36, building: 4, entrance: 15, floor: 9, floors: 9, apartment: 2, rooms: 4, area: '99 - 119.50', price: 118800},
        
        // Building 6, Entrance 604 - 9 floors
        {id: 37, building: 6, entrance: 604, floor: 1, floors: 9, apartment: 3, rooms: 2, area: '57.70 - 69.10', price: 69240},
        {id: 38, building: 6, entrance: 604, floor: 2, floors: 9, apartment: 3, rooms: 2, area: '57.70 - 69.10', price: 69240},
        {id: 39, building: 6, entrance: 604, floor: 3, floors: 9, apartment: 3, rooms: 2, area: '57.70 - 69.10', price: 69240},
        {id: 40, building: 6, entrance: 604, floor: 4, floors: 9, apartment: 3, rooms: 2, area: '57.70 - 69.10', price: 69240},
        {id: 41, building: 6, entrance: 604, floor: 5, floors: 9, apartment: 3, rooms: 2, area: '57.70 - 69.10', price: 69240},
        {id: 42, building: 6, entrance: 604, floor: 6, floors: 9, apartment: 3, rooms: 2, area: '57.70 - 69.10', price: 69240},
        {id: 43, building: 6, entrance: 604, floor: 7, floors: 9, apartment: 3, rooms: 2, area: '57.70 - 69.10', price: 69240},
        {id: 44, building: 6, entrance: 604, floor: 8, floors: 9, apartment: 3, rooms: 2, area: '57.70 - 69.10', price: 69240},
        {id: 45, building: 6, entrance: 604, floor: 9, floors: 9, apartment: 3, rooms: 2, area: '57.70 - 69.10', price: 69240},
        
        // Building 7, Entrance 608 - 9 floors
        {id: 46, building: 7, entrance: 608, floor: 1, floors: 9, apartment: 1, rooms: 3, area: '72.90 - 85.20', price: 87480},
        {id: 47, building: 7, entrance: 608, floor: 2, floors: 9, apartment: 1, rooms: 3, area: '72.90 - 85.20', price: 87480},
        {id: 48, building: 7, entrance: 608, floor: 3, floors: 9, apartment: 1, rooms: 3, area: '72.90 - 85.20', price: 87480},
        {id: 49, building: 7, entrance: 608, floor: 4, floors: 9, apartment: 1, rooms: 3, area: '72.90 - 85.20', price: 87480},
        {id: 50, building: 7, entrance: 608, floor: 5, floors: 9, apartment: 1, rooms: 3, area: '72.90 - 85.20', price: 87480},
        {id: 51, building: 7, entrance: 608, floor: 6, floors: 9, apartment: 1, rooms: 3, area: '72.90 - 85.20', price: 87480},
        {id: 52, building: 7, entrance: 608, floor: 7, floors: 9, apartment: 1, rooms: 3, area: '72.90 - 85.20', price: 87480},
        {id: 53, building: 7, entrance: 608, floor: 8, floors: 9, apartment: 1, rooms: 3, area: '72.90 - 85.20', price: 87480},
        {id: 54, building: 7, entrance: 608, floor: 9, floors: 9, apartment: 1, rooms: 3, area: '72.90 - 85.20', price: 87480},
    ];

    // Function to display apartments
    function displayApartments(apartments) {
        console.log('displayApartments called with:', apartments);
        
        const $apartmentsList = $('#apartments-list');
        console.log('$apartmentsList element:', $apartmentsList, 'length:', $apartmentsList.length);
        
        if (apartments.length === 0) {
            // Show empty state
            $apartmentsList.html(`
                <div class="d-flex h-100 justify-content-center align-items-center py-5">
                    <div class="dashed-border mg-glass-cont success d-flex flex-column align-items-center justify-content-center gap-3" style="width: 33%;">
                        <svg viewBox="0 0 163 109" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 10.1875rem; height: 6.8125rem;">
                            <path d="M153.389 23.9466C153.389 27.4389 151.978 30.5996 149.698 32.8881C147.418 35.1767 144.267 36.5923 140.787 36.5923H23.2696C16.3102 36.5923 10.6689 30.9312 10.6689 23.9473C10.6689 20.455 12.0796 17.2943 14.3602 15.0057C16.6399 12.7172 19.7903 11.3015 23.2696 11.3015H140.787C147.747 11.3015 153.389 16.9627 153.389 23.9466Z" fill="#EAEEF1" fill-opacity="0.4"></path>
                            <path d="M23.2695 11.8015H140.787C147.469 11.8015 152.889 17.2376 152.889 23.947C152.889 27.092 151.698 29.9559 149.744 32.113L149.344 32.5349C147.154 34.7333 144.129 36.0925 140.787 36.0925H23.2695C16.588 36.0925 11.1689 30.6564 11.1689 23.947C11.169 20.802 12.3597 17.9382 14.3135 15.781L14.7139 15.3591L14.7148 15.3582C16.8363 13.2288 19.7406 11.8873 22.957 11.8054L23.2695 11.8015Z" stroke="#EAEEF1" stroke-opacity="0.6"></path>
                            <path d="M37.4757 11.3015H36.8496V36.5923H37.4757V11.3015Z" fill="#F0F4F7"></path>
                            <path d="M127.208 11.3015H126.582V36.5923H127.208V11.3015Z" fill="#F0F4F7"></path>
                            <path d="M28.8932 27.8449L26.4815 25.4247C26.9868 24.7749 27.2891 23.9585 27.2891 23.0722C27.2891 20.9599 25.5761 19.2408 23.4712 19.2408C21.3663 19.2408 19.6533 20.9599 19.6533 23.0722C19.6533 25.1845 21.3663 26.9036 23.4712 26.9036C24.3544 26.9036 25.1679 26.6002 25.8155 26.0931L28.2271 28.5133C28.319 28.6055 28.4398 28.6516 28.5605 28.6516C28.6813 28.6516 28.8021 28.6055 28.8939 28.5133C29.0769 28.3289 29.0769 28.03 28.8932 27.8449ZM20.5958 23.0729C20.5958 21.4818 21.8857 20.1873 23.4712 20.1873C25.0568 20.1873 26.3467 21.4818 26.3467 23.0729C26.3467 24.6641 25.0568 25.9585 23.4712 25.9585C21.8857 25.9585 20.5958 24.6641 20.5958 23.0729Z" fill="#6C767C"></path>
                            <path d="M139.007 25.6582C140.026 25.6582 140.853 24.8284 140.853 23.8053V21.0937C140.853 20.0706 140.026 19.2408 139.007 19.2408C137.987 19.2408 137.16 20.0706 137.16 21.0937V23.8046C137.16 24.8284 137.986 25.6582 139.007 25.6582Z" fill="#6C767C"></path>
                        </svg>
                        <p class="fw-500 text-center">Heç bir nəticə tapılmadı.</p>
                    </div>
                </div>
            `);
            return;
        }
        
        // Build apartments HTML
        let html = '';
        apartments.forEach(function(apt) {
            html += `
                <div class="row apartment-row success m-0" data-apartment-id="${apt.id}">
                    <div class="apartment-row-item" style="width:12.5%; flex: 0 0 auto;">${apt.building}</div>
                    <div class="apartment-row-item" style="width:12.5%; flex: 0 0 auto;">${apt.entrance}</div>
                    <div class="apartment-row-item" style="width:12.5%; flex: 0 0 auto;">${apt.floor} / ${apt.floors}</div>
                    <div class="apartment-row-item" style="width:12.5%; flex: 0 0 auto;">${apt.apartment}</div>
                    <div class="col-2 apartment-row-item">${apt.rooms}</div>
                    <div class="col-2 apartment-row-item">${apt.area}</div>
                    <div class="col-2 apartment-row-item">${apt.price.toLocaleString()}</div>
                </div>
            `;
        });
        
        $apartmentsList.html(html);
    }

    $(document).ready(function() {
        console.log('Mida multi-step form loaded');
        console.log('jQuery version:', $.fn.jquery);
        console.log('apartmentsData available:', typeof apartmentsData, apartmentsData.length);
        
        // Timer variables
        let timerInterval = null;
        let timerStartTime = null;
        let timerRunning = false;
        
        // Timer functions
        function startTimer() {
            if (timerRunning) return;
            
            timerStartTime = Date.now();
            timerRunning = true;
            $('#selection-timer').fadeIn(300);
            
            timerInterval = setInterval(function() {
                const elapsed = Date.now() - timerStartTime;
                const minutes = Math.floor(elapsed / 60000);
                const seconds = Math.floor((elapsed % 60000) / 1000);
                const milliseconds = elapsed % 1000;
                
                const display = 
                    String(minutes).padStart(2, '0') + ':' + 
                    String(seconds).padStart(2, '0') + ':' + 
                    String(milliseconds).padStart(3, '0');
                
                $('#timer-display').text(display);
            }, 10); // Update every 10ms for smoother milliseconds display
        }
        
        function stopTimer() {
            if (!timerRunning) return;
            
            timerRunning = false;
            clearInterval(timerInterval);
            
            const elapsed = Date.now() - timerStartTime;
            const minutes = Math.floor(elapsed / 60000);
            const seconds = Math.floor((elapsed % 60000) / 1000);
            const milliseconds = elapsed % 1000;
            
            const timeDisplay = String(minutes).padStart(2, '0') + ':' + 
                               String(seconds).padStart(2, '0') + ':' + 
                               String(milliseconds).padStart(3, '0');
            
            console.log('Timer stopped. Total time:', minutes + 'm ' + seconds + 's ' + milliseconds + 'ms');
            
            // Change timer color to indicate completion
            $('#timer-display').css('color', '#28a745');
            
            // Return the timer data
            return {
                elapsed_ms: elapsed,
                display: timeDisplay,
                minutes: minutes,
                seconds: seconds,
                milliseconds: milliseconds
            };
        }
        
        // Start button handler
        $('#start-btn').on('click', function() {
            $('#start-screen').fadeOut(300, function() {
                $('#main-form-container').fadeIn(300, function() {
                    // Start timer after form is visible
                    startTimer();
                });
            });
        });
        
        // Test if button exists and add inline handler
        setTimeout(function() {
            const $btn = $('#search-apartments');
            console.log('Search button exists:', $btn.length);
            if ($btn.length) {
                console.log('Button HTML:', $btn[0].outerHTML);
                // Add direct handler
                $btn.off('click').on('click', function(e) {
                    console.log('=== DIRECT HANDLER: SEARCH BUTTON CLICKED ===');
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const selectedRooms = [];
                    $('#step-axtaris .list-group-item[data-rooms].active').each(function() {
                        const roomValue = $(this).data('rooms');
                        console.log('Room element:', this, 'data-rooms value:', roomValue, 'type:', typeof roomValue);
                        selectedRooms.push(String(roomValue));
                    });
                    
                    console.log('selectedRooms array:', selectedRooms);
                    
                    const minFloor = $('#step-axtaris select[name="min_floor"]').val();
                    const maxFloor = $('#step-axtaris select[name="max_floor"]').val();
                    const buildingType = $('#step-axtaris .list-group-item[data-building-type].active').data('building-type');
                    
                    console.log('Search params:', {
                        buildingType: buildingType,
                        rooms: selectedRooms,
                        minFloor: minFloor,
                        maxFloor: maxFloor
                    });
                    
                    // Filter apartments
                    const filteredApartments = apartmentsData.filter(function(apt) {
                        console.log('Checking apartment:', apt.id, {
                            aptFloors: apt.floors,
                            buildingType: buildingType,
                            floorsMatch: apt.floors === parseInt(buildingType),
                            aptFloor: apt.floor,
                            minFloor: minFloor,
                            maxFloor: maxFloor,
                            aptRooms: apt.rooms,
                            aptRoomsStr: apt.rooms.toString(),
                            selectedRooms: selectedRooms,
                            roomsIncluded: selectedRooms.includes(apt.rooms.toString())
                        });
                        
                        if (buildingType && apt.floors !== parseInt(buildingType)) {
                            console.log('  -> Rejected: building type mismatch');
                            return false;
                        }
                        if (minFloor && apt.floor < parseInt(minFloor)) {
                            console.log('  -> Rejected: floor too low');
                            return false;
                        }
                        if (maxFloor && apt.floor > parseInt(maxFloor)) {
                            console.log('  -> Rejected: floor too high');
                            return false;
                        }
                        if (selectedRooms.length > 0 && !selectedRooms.includes(apt.rooms.toString())) {
                            console.log('  -> Rejected: room count mismatch');
                            return false;
                        }
                        console.log('  -> ACCEPTED');
                        return true;
                    });
                    
                    console.log('Filtered apartments:', filteredApartments);
                    
                    // Show loading state
                    const $apartmentsList = $('#apartments-list');
                    $apartmentsList.html(`
                        <div class="d-flex h-100 justify-content-center align-items-center py-5">
                            <div class="d-flex flex-column align-items-center justify-content-center gap-3">
                                <div class="spinner-border text-success" role="status">
                                    <span class="visually-hidden">Yüklənir...</span>
                                </div>
                                <p class="fw-500 text-center">Axtarış nəticələri yüklənir...</p>
                            </div>
                        </div>
                    `);
                    
                    // Display results after 1 second delay
                    setTimeout(function() {
                        displayApartments(filteredApartments);
                    }, 1000);
                });
            }
        }, 2000);

        let currentStep = 'secimler'; // Current step ID
        let showSearchStep = false; // Flag for Axtarış step

        // ==================== STEP 1: SEQUENTIAL VALIDATION ====================
        
        // Project selection enables payment method
        $('select[autocomplete="off"]').on('change', function() {
            const projectSelected = $(this).val();
            if (projectSelected) {
                // Enable payment method radio buttons
                $('#cash-payment-choice, #loan-payment-choice').prop('disabled', false);
            } else {
                // Disable payment method and flat selection
                $('#cash-payment-choice, #loan-payment-choice').prop('disabled', true).prop('checked', false);
                $('input[name="flat-selection"]').prop('disabled', true).prop('checked', false);
                $('#btn-next-step1').prop('disabled', true);
            }
        });

        // Payment method selection enables flat selection method
        $('input[name="payment-method"]').on('change', function() {
            const paymentSelected = $('input[name="payment-method"]:checked').length > 0;
            if (paymentSelected) {
                // Enable flat selection radio buttons
                $('input[name="flat-selection"]').prop('disabled', false);
            } else {
                $('input[name="flat-selection"]').prop('disabled', true).prop('checked', false);
                $('#btn-next-step1').prop('disabled', true);
            }
        });

        // Handle selection method change
        $('input[name="flat-selection"]').on('change', function() {
            const selectedMethod = $(this).attr('id');
            
            if (selectedMethod === 'explorer-search') {
                // Show Axtarış step (Parametrlər üzrə)
                showSearchStep = true;
                $('.bc-item[data-step="0"]').show(); // Show search breadcrumb
                updateBreadcrumbNumbers();
            } else {
                // Hide Axtarış step (Xəritə üzərində or Ünvan üzrə)
                showSearchStep = false;
                $('.bc-item[data-step="0"]').hide(); // Hide search breadcrumb
                updateBreadcrumbNumbers();
            }
            
            // Enable Next button when selection is made
            $('#btn-next-step1').prop('disabled', false);
        });

        // ==================== STEP 1 NAVIGATION ====================

        // Next button from Step 1 (Seçimlər)
        $('#btn-next-step1').on('click', function(e) {
            e.preventDefault();
            
            console.log('Next button clicked');
            
            // Check if flat-selection is chosen
            const selectedMethod = $('input[name="flat-selection"]:checked').attr('id');
            
            console.log('Selected method:', selectedMethod);
            
            if (!selectedMethod) {
                alert('Xahiş olunur seçim metodunu seçin.');
                return;
            }
            
            // Hide Step 1
            $('#step-secimler').hide();
            
            if (selectedMethod === 'explorer-search') {
                // Show Axtarış step
                $('#step-axtaris').show();
                currentStep = 'axtaris';
                updateBreadcrumbForStep(2);
            } else {
                // Go directly to Mənzil step (to be implemented)
                // TODO: Show Mənzil step
                alert('Mənzil seçimi tezliklə əlavə ediləcək.');
                $('#step-secimler').show(); // Show step 1 again
            }
        });

        // ==================== STEP 2: SEQUENTIAL VALIDATION ====================

        // Building type selection (9 mərtəbəli) - enables floor selection
        $('#step-axtaris .list-group-item[data-building-type]').on('click', function() {
            const $item = $(this);
            
            // Only one building type can be selected
            $('#step-axtaris .list-group-item[data-building-type]').removeClass('active');
            $item.addClass('active');
            
            // Enable floor selection dropdowns
            $('#step-axtaris select[name="min_floor"], #step-axtaris select[name="max_floor"]').prop('disabled', false);
            
            // Update search button state
            updateSearchButtonState();
        });

        // Floor selection - enables room selection
        $('#step-axtaris select[name="min_floor"], #step-axtaris select[name="max_floor"]').on('change', function() {
            const minFloor = $('#step-axtaris select[name="min_floor"]').val();
            const maxFloor = $('#step-axtaris select[name="max_floor"]').val();
            
            if (minFloor && maxFloor) {
                // Enable room selection
                $('#step-axtaris .list-group-item[data-rooms]').removeClass('disabled');
            } else {
                // Disable room selection if floors not fully selected
                $('#step-axtaris .list-group-item[data-rooms]').addClass('disabled').removeClass('active');
            }
            
            updateSearchButtonState();
        });

        // Room selection in Axtarış step
        $('#step-axtaris .list-group-item[data-rooms]').on('click', function() {
            const $item = $(this);
            
            // Don't allow click if disabled
            if ($item.hasClass('disabled')) {
                return;
            }
            
            $item.toggleClass('active');
            
            // Enable/disable search button based on selection
            updateSearchButtonState();
        });

        // ==================== STEP 2 NAVIGATION ====================

        // Back button from Axtarış step
        $('#back-to-options').on('click', function(e) {
            e.preventDefault();
            $('#step-axtaris').hide();
            $('#step-secimler').show();
            currentStep = 'secimler';
            updateBreadcrumbForStep(1);
        });

        // Update search button state
        window.updateSearchButtonState = function() {
            const buildingSelected = $('#step-axtaris .list-group-item[data-building-type].active').length > 0;
            const hasRoomSelection = $('#step-axtaris .list-group-item[data-rooms].active').length > 0;
            const minFloor = $('#step-axtaris select[name="min_floor"]').val();
            const maxFloor = $('#step-axtaris select[name="max_floor"]').val();
            
            console.log('updateSearchButtonState:', {
                buildingSelected: buildingSelected,
                hasRoomSelection: hasRoomSelection,
                minFloor: minFloor,
                maxFloor: maxFloor
            });
            
            // Enable search only if building type selected AND rooms selected AND captcha verified
            // Floor selection is optional
            const captchaVerified = $('#captcha-verified-step2').val() === '1';
            
            if (buildingSelected && hasRoomSelection && captchaVerified) {
                console.log('Enabling search button - all conditions met');
                $('#search-apartments').prop('disabled', false);
            } else {
                console.log('Disabling search button - missing:', {
                    buildingSelected,
                    hasRoomSelection,
                    captchaVerified
                });
                $('#search-apartments').prop('disabled', true);
            }
        }

        // Reset filters
        $('#reset-filters').on('click', function(e) {
            e.preventDefault();
            
            // Reset all selections
            $('#step-axtaris .list-group-item').removeClass('active');
            $('#step-axtaris select').val('').prop('disabled', true);
            
            // Re-enable only building type selection
            $('#step-axtaris .list-group-item[data-building-type]').removeClass('disabled');
            
            // Disable room selection again
            $('#step-axtaris .list-group-item[data-rooms]').addClass('disabled');
            
            $('#search-apartments').prop('disabled', true);
        });

        // Test: click on wrapper div
        $(document).on('click', '.cursor:has(#search-apartments)', function(e) {
            console.log('Wrapper div clicked!', e.target);
        });

        // Search apartments - use event delegation since button is in hidden step
        $(document).on('click', '#search-apartments', function(e) {
            console.log('=== SEARCH BUTTON CLICKED ===');
            e.preventDefault();
            e.stopPropagation();
            console.log('Event:', e);
            console.log('Button element:', this);
            console.log('Button disabled?', $(this).prop('disabled'));
            console.log('apartmentsData length:', apartmentsData.length);
            
            const selectedRooms = [];
            $('#step-axtaris .list-group-item[data-rooms].active').each(function() {
                selectedRooms.push($(this).data('rooms'));
            });
            
            const minFloor = $('#step-axtaris select[name="min_floor"]').val();
            const maxFloor = $('#step-axtaris select[name="max_floor"]').val();
            const buildingType = $('#step-axtaris .list-group-item[data-building-type].active').data('building-type');
            
            console.log('Search params:', {
                buildingType: buildingType,
                rooms: selectedRooms,
                minFloor: minFloor,
                maxFloor: maxFloor
            });
            
            // Filter apartments based on search criteria
            const filteredApartments = apartmentsData.filter(function(apt) {
                // Filter by building type (9 = 9 floors building)
                if (buildingType && apt.floors !== parseInt(buildingType)) {
                    return false;
                }
                
                // Filter by floor range
                if (minFloor && apt.floor < parseInt(minFloor)) {
                    return false;
                }
                if (maxFloor && apt.floor > parseInt(maxFloor)) {
                    return false;
                }
                
                // Filter by room count
                if (selectedRooms.length > 0 && !selectedRooms.includes(apt.rooms.toString())) {
                    return false;
                }
                
                return true;
            });
            
            console.log('Filtered apartments:', filteredApartments);
            console.log('displayApartments function:', typeof displayApartments);
            
            // Display results
            displayApartments(filteredApartments);
        });

        // Apartment row click handler - Navigate to Step 3
        $(document).on('click', '.apartment-row', function() {
            const apartmentId = $(this).data('apartment-id');
            const selectedApartment = apartmentsData.find(apt => apt.id === apartmentId);
            
            console.log('Selected apartment:', selectedApartment);
            
            // Stop the timer and get timer data
            const timerData = stopTimer();
            
            if (timerData) {
                // Get form selections
                const layihe = $('select[autocomplete="off"]').val() || '';
                const odenishUsulu = $('input[name="payment-method"]:checked').val() || '';
                const otaqSayi = selectedApartment.rooms || '';
                const mertebe = selectedApartment.floor || '';
                
                // Save selection with timer data to database
                $.ajax({
                    url: midaAjax.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'mida_save_selection',
                        nonce: midaAjax.save_selection_nonce,
                        selection_time_ms: timerData.elapsed_ms,
                        selection_time_display: timerData.display,
                        layihe: layihe,
                        odenish_usulu: odenishUsulu,
                        otaq_sayi: otaqSayi,
                        mertebe: mertebe
                    },
                    success: function(response) {
                        console.log('Selection saved:', response);
                        if (response.success) {
                            // Store submission ID for later use
                            sessionStorage.setItem('mida_submission_id', response.data.submission_id);
                            
                            // Check for warnings
                            if (response.data.has_warning && response.data.warnings.length > 0) {
                                let warningMsg = 'XƏBƏRDARLIQ: Seçiminiz qeydə alındı, lakin reytinqə daxil olmayacaq.\n\n';
                                response.data.warnings.forEach(function(w) {
                                    warningMsg += w.type + ': Gözlənilən "' + w.expected + '", Seçilən "' + w.actual + '"\n';
                                });
                                alert(warningMsg);
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error saving selection:', error);
                    }
                });
            }
            
            // Store selected apartment data
            sessionStorage.setItem('selected_apartment', JSON.stringify(selectedApartment));
            sessionStorage.setItem('selection_time', JSON.stringify(timerData));
            
            // Show alert and refresh page after user clicks OK
            alert('Mənzil seçildi: Bina ' + selectedApartment.building + ', Giriş ' + selectedApartment.entrance + ', Mərtəbə ' + selectedApartment.floor + '\nSeçim müddəti: ' + timerData.display);
            
            // Refresh page after 1 second
            setTimeout(function() {
                location.reload();
            }, 1000);
        });

        // ==================== BREADCRUMB FUNCTIONS ====================

        // Update breadcrumb step numbers when Axtarış is shown/hidden
        function updateBreadcrumbNumbers() {
            if (showSearchStep) {
                // Show 4 steps: Seçimlər(1) -> Axtarış(2) -> Mənzil(3) -> Ərizə(4)
                $('.bc-item').each(function() {
                    const originalStep = $(this).data('original-step');
                    if (originalStep === 1) {
                        $(this).find('span').first().text('1');
                    } else if (originalStep === 0) {
                        $(this).find('span').first().text('2');
                    } else if (originalStep === 2) {
                        $(this).find('span').first().text('3');
                    } else if (originalStep === 3) {
                        $(this).find('span').first().text('4');
                    }
                });
            } else {
                // Show 3 steps: Seçimlər(1) -> Mənzil(2) -> Ərizə(3)
                $('.bc-item').each(function() {
                    const originalStep = $(this).data('original-step');
                    if (originalStep === 1) {
                        $(this).find('span').first().text('1');
                    } else if (originalStep === 2) {
                        $(this).find('span').first().text('2');
                    } else if (originalStep === 3) {
                        $(this).find('span').first().text('3');
                    }
                });
            }
        }

        // Initialize breadcrumb with data attributes
        $('.bc-item').each(function() {
            const $item = $(this);
            const stepText = $item.find('span').first().text();
            $item.attr('data-original-step', stepText);
            $item.attr('data-step', stepText);
        });

        // Update breadcrumb for specific step
        function updateBreadcrumbForStep(activeStepNum) {
            $('.bc-item').each(function() {
                const $item = $(this);
                let stepNum;
                
                if (showSearchStep) {
                    // 4-step mode
                    const originalStep = $item.data('original-step');
                    if (originalStep === 1) stepNum = 1;
                    else if (originalStep === 0) stepNum = 2;
                    else if (originalStep === 2) stepNum = 3;
                    else if (originalStep === 3) stepNum = 4;
                } else {
                    // 3-step mode
                    stepNum = $item.data('original-step');
                }
                
                $item.removeClass('success pending');
                
                if (stepNum < activeStepNum) {
                    $item.addClass('success checked');
                } else if (stepNum === activeStepNum) {
                    $item.addClass('success pending');
                }
            });
        }

        // Initialize
        updateBreadcrumbForStep(1);
    });

    // ==================== BIRTHDATE CAPTCHA ====================
    $(document).ready(function() {
        let dayInput = '';
        let monthInput = '';
        let yearInput = '';
        let captchaDay = 1;
        let captchaMonth = 1;
        let captchaYear = 2000;
        let currentCaptchaType = 1;
        
        // Shuffle array function
        function shuffleArray(array) {
            const arr = [...array];
            for (let i = arr.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [arr[i], arr[j]] = [arr[j], arr[i]];
            }
            return arr;
        }
        
        // Get random captcha type (1-2)
        function getRandomCaptchaType() {
            return Math.floor(Math.random() * 2) + 1;
        }
        
        // Month names in Azerbaijani
        const monthNamesShort = ['Yan.', 'Fev.', 'Mar.', 'Apr.', 'May.', 'İyun.', 'İyul.', 'Avq.', 'Sen.', 'Okt.', 'Noy.', 'Dek.'];
        
        // Generate number pad with shuffled numbers
        function generateNumPad(containerId, inputFieldId, maxLength, inputType) {
            const numbers = shuffleArray([0, 1, 2, 3, 4, 5, 6, 7, 8, 9]);
            const container = $(containerId);
            container.empty();
            
            // Always generate 3x4 grid: 10 numbers + clear + submit
            // Add 9 number buttons
            numbers.slice(0, 9).forEach(num => {
                const btn = $('<button>')
                    .attr('type', 'button')
                    .addClass('numpad-btn')
                    .attr('data-num', num)
                    .attr('data-input-type', inputType)
                    .css({
                        'background': '#fff',
                        'color': '#212529',
                        'border': '2px solid #dee2e6',
                        'padding': '12px',
                        'border-radius': '4px',
                        'cursor': 'pointer',
                        'font-size': '18px',
                        'font-weight': '600',
                        'transition': 'all 0.2s'
                    })
                    .text(num);
                container.append(btn);
            });
            
            // Add clear button (red X)
            const clearBtn = $('<button>')
                .attr('type', 'button')
                .attr('id', containerId.replace('#', '') + '-clear')
                .attr('data-input-type', inputType)
                .css({
                    'background': '#dc3545',
                    'color': '#fff',
                    'border': '2px solid #dc3545',
                    'padding': '12px',
                    'border-radius': '4px',
                    'cursor': 'pointer',
                    'font-size': '18px',
                    'font-weight': '600',
                    'transition': 'all 0.2s'
                })
                .text('✕');
            container.append(clearBtn);
            
            // Add last number
            const lastNumBtn = $('<button>')
                .attr('type', 'button')
                .addClass('numpad-btn')
                .attr('data-num', numbers[9])
                .attr('data-input-type', inputType)
                .css({
                    'background': '#fff',
                    'color': '#212529',
                    'border': '2px solid #dee2e6',
                    'padding': '12px',
                    'border-radius': '4px',
                    'cursor': 'pointer',
                    'font-size': '18px',
                    'font-weight': '600',
                    'transition': 'all 0.2s'
                })
                .text(numbers[9]);
            container.append(lastNumBtn);
            
            // Add backspace button - for all input types
            const backspaceBtn = $('<button>')
                .attr('type', 'button')
                .attr('id', containerId.replace('#', '') + '-backspace')
                .attr('data-input-type', inputType)
                .css({
                    'background': '#ffc107',
                    'color': '#000',
                    'border': '2px solid #ffc107',
                    'padding': '12px',
                    'border-radius': '4px',
                    'cursor': 'pointer',
                    'font-size': '18px',
                    'transition': 'all 0.2s',
                    'font-weight': '600'
                })
                .text('⌫');
            container.append(backspaceBtn);
            
            // Add empty placeholder for grid alignment
            const emptyBtn = $('<div>')
                .css({
                    'background': 'transparent',
                    'border': 'none'
                });
            container.append(emptyBtn);
            
            // Add hover effects
            container.find('button').hover(
                function() {
                    $(this).css({
                        'transform': 'scale(1.05)',
                        'box-shadow': '0 4px 12px rgba(0,0,0,0.15)'
                    });
                },
                function() {
                    $(this).css({
                        'transform': 'scale(1)',
                        'box-shadow': 'none'
                    });
                }
            );
            
            // Number button click
            container.on('click', '.numpad-btn', function() {
                const num = $(this).data('num');
                const type = $(this).data('input-type');
                const isStep2 = inputFieldId.includes('step2');
                
                // Use appropriate variables based on which step
                let currentDayInput = isStep2 ? (window.dayInputStep2 || '') : dayInput;
                let currentMonthInput = isStep2 ? (window.monthInputStep2 || '') : monthInput;
                let currentYearInput = isStep2 ? (window.yearInputStep2 || '') : yearInput;
                
                if (type === 'day') {
                    // Auto-switch to month after 2 digits
                    if (currentDayInput.length < 2) {
                        currentDayInput += num;
                        $(inputFieldId).text(currentDayInput);
                        
                        // Update the appropriate variable
                        if (isStep2) {
                            window.dayInputStep2 = currentDayInput;
                        } else {
                            dayInput = currentDayInput;
                        }
                        
                        // Switch to month input after 2 digits
                        if (currentDayInput.length === 2) {
                            $(containerId).find('.numpad-btn').data('input-type', 'month');
                            $(containerId).find('[id$="-clear"]').data('input-type', 'month');
                            $(containerId).find('[id$="-backspace"]').data('input-type', 'month');
                        }
                    }
                } else if (type === 'month') {
                    // Auto-switch to year after 2 digits
                    if (currentMonthInput.length < 2) {
                        currentMonthInput += num;
                        $(inputFieldId.replace('day', 'month')).text(currentMonthInput);
                        
                        // Update the appropriate variable
                        if (isStep2) {
                            window.monthInputStep2 = currentMonthInput;
                        } else {
                            monthInput = currentMonthInput;
                        }
                        
                        // Switch to year input after 2 digits
                        if (currentMonthInput.length === 2) {
                            $(containerId).find('.numpad-btn').data('input-type', 'year');
                            $(containerId).find('[id$="-clear"]').data('input-type', 'year');
                            $(containerId).find('[id$="-backspace"]').data('input-type', 'year');
                        }
                    }
                } else if (type === 'year') {
                    if (currentYearInput.length < 4) {
                        currentYearInput += num;
                        $(inputFieldId.replace('day', 'year')).text(currentYearInput);
                        
                        // Update the appropriate variable
                        if (isStep2) {
                            window.yearInputStep2 = currentYearInput;
                        } else {
                            yearInput = currentYearInput;
                        }
                        
                        // Auto-submit when year is complete
                        if (currentYearInput.length === 4) {
                            if (isStep2) {
                                validateCaptchaStep2();
                            } else {
                                validateCaptcha();
                            }
                        }
                    }
                } else if (type === 'full') {
                    const $field = $(inputFieldId);
                    const currentVal = $field.text().replace(/--\.--.----/, '');
                    const digitsOnly = currentVal.replace(/\./g, '');
                    const isStep2 = inputFieldId.includes('step2');
                    
                    if (digitsOnly.length < 8) {
                        // For DD.MM.YYYY format, automatically insert dots
                        let newDigits = digitsOnly + num;
                        
                        // Format with dots: DD.MM.YYYY
                        let formatted = newDigits;
                        if (newDigits.length > 2) {
                            formatted = newDigits.substring(0, 2) + '.' + newDigits.substring(2);
                        }
                        if (newDigits.length > 4) {
                            formatted = newDigits.substring(0, 2) + '.' + newDigits.substring(2, 4) + '.' + newDigits.substring(4);
                        }
                        $field.text(formatted);
                        
                        // Auto-submit when complete
                        if (newDigits.length === 8) {
                            if (isStep2) {
                                validateCaptchaStep2();
                            } else {
                                validateCaptcha();
                            }
                        }
                    }
                }
                const errorDiv = inputFieldId.includes('step2') ? '#captcha-error-step2' : '#captcha-error';
                $(errorDiv).hide();
            });
            
            // Clear button
            container.on('click', '[id$="-clear"]', function() {
                const type = $(this).data('input-type');
                const isStep2 = inputFieldId.includes('step2');
                
                if (type === 'day' || type === 'month' || type === 'year') {
                    // Reset all inputs
                    if (isStep2) {
                        window.dayInputStep2 = '';
                        window.monthInputStep2 = '';
                        window.yearInputStep2 = '';
                        $('#captcha-day-input-step2').text('--');
                        $('#captcha-month-input-step2').text('--');
                        $('#captcha-year-input-step2').text('----');
                    } else {
                        dayInput = '';
                        monthInput = '';
                        yearInput = '';
                        $('#captcha-day-input').text('--');
                        $('#captcha-month-input').text('--');
                        $('#captcha-year-input').text('----');
                    }
                    // Always reset to day input
                    $(containerId).find('.numpad-btn').data('input-type', 'day');
                    $(this).data('input-type', 'day');
                    $(containerId).find('[id$="-backspace"]').data('input-type', 'day');
                } else if (type === 'full') {
                    // Clear all for single input captchas
                    $(inputFieldId).text('--.--.----');
                }
                const errorDiv = isStep2 ? '#captcha-error-step2' : '#captcha-error';
                $(errorDiv).hide();
            });
            
            // Backspace button
            container.on('click', '[id$="-backspace"]', function() {
                const type = $(this).data('input-type');
                const isStep2 = inputFieldId.includes('step2');
                
                if (type === 'day') {
                    const currentDayInput = isStep2 ? (window.dayInputStep2 || '') : dayInput;
                    if (currentDayInput.length > 0) {
                        const newVal = currentDayInput.slice(0, -1);
                        if (isStep2) {
                            window.dayInputStep2 = newVal;
                            $('#captcha-day-input-step2').text(newVal || '--');
                        } else {
                            dayInput = newVal;
                            $('#captcha-day-input').text(newVal || '--');
                        }
                    }
                } else if (type === 'month') {
                    const currentMonthInput = isStep2 ? (window.monthInputStep2 || '') : monthInput;
                    if (currentMonthInput.length > 0) {
                        const newVal = currentMonthInput.slice(0, -1);
                        if (isStep2) {
                            window.monthInputStep2 = newVal;
                            $('#captcha-month-input-step2').text(newVal || '--');
                        } else {
                            monthInput = newVal;
                            $('#captcha-month-input').text(newVal || '--');
                        }
                        
                        // Switch back to day if month becomes empty
                        if (newVal.length === 0) {
                            $(containerId).find('.numpad-btn').data('input-type', 'day');
                            $(containerId).find('[id$="-clear"]').data('input-type', 'day');
                            $(this).data('input-type', 'day');
                        }
                    }
                } else if (type === 'year') {
                    const currentYearInput = isStep2 ? (window.yearInputStep2 || '') : yearInput;
                    if (currentYearInput.length > 0) {
                        const newVal = currentYearInput.slice(0, -1);
                        if (isStep2) {
                            window.yearInputStep2 = newVal;
                            $('#captcha-year-input-step2').text(newVal || '----');
                        } else {
                            yearInput = newVal;
                            $('#captcha-year-input').text(newVal || '----');
                        }
                        
                        // Switch back to month if year becomes empty
                        if (newVal.length === 0) {
                            $(containerId).find('.numpad-btn').data('input-type', 'month');
                            $(containerId).find('[id$="-clear"]').data('input-type', 'month');
                            $(this).data('input-type', 'month');
                        }
                    }
                } else if (type === 'full') {
                    const $field = $(inputFieldId);
                    const currentVal = $field.text().replace(/--\.--\.----/, '');
                    const digitsOnly = currentVal.replace(/\./g, '');
                    
                    if (digitsOnly.length > 0) {
                        const newDigits = digitsOnly.slice(0, -1);
                        
                        if (newDigits.length === 0) {
                            $field.text('--.--.----');
                        } else {
                            // Reformat with dots
                            let formatted = newDigits;
                            if (newDigits.length > 2) {
                                formatted = newDigits.substring(0, 2) + '.' + newDigits.substring(2);
                            }
                            if (newDigits.length > 4) {
                                formatted = newDigits.substring(0, 2) + '.' + newDigits.substring(2, 4) + '.' + newDigits.substring(4);
                            }
                            $field.text(formatted);
                        }
                    }
                }
                const errorDiv = isStep2 ? '#captcha-error-step2' : '#captcha-error';
                $(errorDiv).hide();
            });
        }
        
        // Update step 1 validation to include captcha
        function validateStep1() {
            const projectSelected = $('select[autocomplete="off"]').val();
            const paymentSelected = $('input[name="payment-method"]:checked').val();
            const captchaVerified = $('#captcha-verified').val() === '1';
            
            console.log('=== validateStep1 Debug ===');
            console.log('Project selected:', projectSelected);
            console.log('Payment selected:', paymentSelected);
            console.log('Captcha verified value:', $('#captcha-verified').val());
            console.log('Captcha verified (boolean):', captchaVerified);
            console.log('All conditions met:', projectSelected && paymentSelected && captchaVerified);
            
            if (projectSelected && paymentSelected && captchaVerified) {
                console.log('✓ Enabling next button');
                $('#btn-next-step1').prop('disabled', false);
            } else {
                console.log('✗ Disabling next button');
                $('#btn-next-step1').prop('disabled', true);
            }
        }
        
        // Validate captcha
        function validateCaptcha() {
            let userAnswer = '';
            const paddedDay = String(captchaDay).padStart(2, '0');
            const paddedMonth = String(captchaMonth).padStart(2, '0');
            const correctAnswer = paddedDay + paddedMonth + captchaYear; // e.g., "10011985"
            
            // Get user answer based on captcha type
            switch(currentCaptchaType) {
                case 1: // Day/Month/Year with separate numpads
                    const enteredDay = dayInput.padStart(2, '0');
                    const enteredMonth = monthInput.padStart(2, '0');
                    const enteredYear = yearInput;
                    
                    if (!dayInput || !monthInput || !yearInput) {
                        $('#captcha-error').css('color', '#dc3545').text('Bütün sahələri doldurun!').show();
                        return;
                    }
                    
                    if (dayInput.length < 1 || dayInput.length > 2) {
                        $('#captcha-error').css('color', '#dc3545').text('Gün 2 rəqəmdən ibarət olmalıdır!').show();
                        return;
                    }
                    
                    if (monthInput.length < 1 || monthInput.length > 2) {
                        $('#captcha-error').css('color', '#dc3545').text('Ay 2 rəqəmdən ibarət olmalıdır!').show();
                        return;
                    }
                    
                    if (yearInput.length !== 4) {
                        $('#captcha-error').css('color', '#dc3545').text('İl 4 rəqəmdən ibarət olmalıdır!').show();
                        return;
                    }
                    
                    userAnswer = enteredDay + enteredMonth + enteredYear;
                    break;
                    
                case 2: // DD.MM.YYYY format with dots
                    userAnswer = $('#captcha-full-input').text().replace(/\./g, '').replace(/-/g, '').trim();
                    
                    if (!userAnswer) {
                        $('#captcha-error').css('color', '#dc3545').text('Cavabı daxil edin!').show();
                        return;
                    }
                    
                    if (userAnswer.length !== 8) {
                        $('#captcha-error').css('color', '#dc3545').text('Tam tarixi daxil edin (GG.AA.YYYY)!').show();
                        return;
                    }
                    break;
            }
            
            // Check if answer is correct
            const isCorrect = (userAnswer === correctAnswer);
            
            // Handle result
            if (isCorrect) {
                console.log('=== Captcha Success ===');
                console.log('Setting captcha-verified to 1');
                $('#captcha-verified').val('1');
                console.log('Captcha-verified value after set:', $('#captcha-verified').val());
                
                $('#captcha-error').text('').hide();
                $('#birthdate-captcha').css({
                    'border': '2px solid #28a745',
                    'background': '#d4edda'
                });
                
                // Show success message
                $('#captcha-error').css('color', '#28a745').text('✓ Doğum tarixi təsdiqləndi!').show();
                
                // Enable next button
                console.log('Calling validateStep1()...');
                validateStep1();
            } else {
                console.log('=== Captcha Failed ===');
                console.log('User answer:', userAnswer);
                console.log('Correct answer:', correctAnswer);
                $('#captcha-verified').val('0');
                $('#captcha-error').css('color', '#dc3545').text('Yanlış cavab! Yenidən cəhd edin.').show();
                
                // Reset inputs
                dayInput = '';
                monthInput = '';
                yearInput = '';
                $('#captcha-day-input').text('--');
                $('#captcha-month-input').text('--');
                $('#captcha-year-input').text('----');
                $('#captcha-full-input').text('--.--.----');
                
                // Reset numpad input type back to 'day' for type 1 captcha
                if (currentCaptchaType === 1) {
                    $('#shared-numpad').find('.numpad-btn').data('input-type', 'day');
                    $('#shared-numpad').find('[id$="-clear"]').data('input-type', 'day');
                    $('#shared-numpad').find('[id$="-backspace"]').data('input-type', 'day');
                }
                
                $('#birthdate-captcha').css({
                    'border': '2px solid #dc3545',
                    'background': '#f8d7da'
                });
            }
        }
        
        // Initialize with user's actual birthdate from restrictions
        if (typeof midaUserRestrictions !== 'undefined' && midaUserRestrictions.birthdate) {
            const birthParts = midaUserRestrictions.birthdate.split('-'); // YYYY-MM-DD
            if (birthParts.length === 3) {
                captchaYear = parseInt(birthParts[0]);
                captchaMonth = parseInt(birthParts[1]);
                captchaDay = parseInt(birthParts[2]);
                
                $('#captcha-answer').val(captchaDay + '/' + captchaMonth + '/' + captchaYear);
            }
        } else {
            // Fallback: should not happen if admin configured properly
            captchaDay = 15;
            captchaMonth = 6;
            captchaYear = 1990;
            $('#captcha-answer').val(captchaDay + '/' + captchaMonth + '/' + captchaYear);
        }
        
        // Render captcha based on type
        function renderCaptcha() {
            const container = $('#birthdate-captcha > div');
            container.empty();
            
            const paddedDay = String(captchaDay).padStart(2, '0');
            const paddedMonth = String(captchaMonth).padStart(2, '0');
            
            switch(currentCaptchaType) {
                case 1: // Day/Month/Year with 3 separate numpads
                    container.html(`
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                            <div style="color: #495057; font-size: 14px; width: 100%;">
                                <p style="margin: 0; line-height: 1.6; width: 100%; font-weight: 500;">Doğum tarixinizi daxil edin (G\u00fcn-Ay-\u0130l):</p>
                            </div>
                            <div style="background: #fff; border: 2px solid #ced4da; border-radius: 4px; padding: 8px 15px; display: flex; gap: 5px; align-items: center; flex-shrink: 0;">
                                <span id="captcha-day-input" style="color: #212529; font-size: 20px; min-width: 30px; text-align: center; font-weight: 600;">--</span>
                                <span style="color: #adb5bd;">/</span>
                                <span id="captcha-month-input" style="color: #212529; font-size: 20px; min-width: 30px; text-align: center; font-weight: 600;">--</span>
                                <span style="color: #adb5bd;">/</span>
                                <span id="captcha-year-input" style="color: #212529; font-size: 20px; min-width: 50px; text-align: center; font-weight: 600;">----</span>
                            </div>
                        </div>
                        <div style="max-width: 300px;">
                            <div id="shared-numpad" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 5px;"></div>
                        </div>
                    `);
                    generateNumPad('#shared-numpad', '#captcha-day-input', 2, 'day');
                    break;
                
                case 2: // DD.MM.YYYY format with dots
                    container.html(`
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                            <div style="color: #495057; font-size: 16px; width: 100%;">
                                <p style="margin: 0; line-height: 1.6; font-weight: 600; color: #212529; width: 100%;">Doğum tarixinizi GG.AA.YYYY formatında daxil edin:</p>
                            </div>
                            <div style="background: #fff; border: 2px solid #ced4da; border-radius: 4px; padding: 8px 15px; display: flex; gap: 5px; align-items: center; flex-shrink: 0;">
                                <span id="captcha-full-input" style="color: #212529; font-size: 20px; min-width: 120px; text-align: center; font-weight: 600;">--.--.----</span>
                            </div>
                        </div>
                        <div style="max-width: 300px;">
                            <div id="full-numpad" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 5px;"></div>
                        </div>
                    `);
                    generateNumPad('#full-numpad', '#captcha-full-input', 10, 'full');
                    break;
            }
            
            // Add error message div
            container.append('<div id="captcha-error" style="color: #dc3545; font-size: 14px; margin-top: 20px; text-align: center; display: none;"></div>');
        }
        
        // Initialize with user's actual birthdate from restrictions
        if (typeof midaUserRestrictions !== 'undefined' && midaUserRestrictions.birthdate) {
            const birthParts = midaUserRestrictions.birthdate.split('-'); // YYYY-MM-DD
            if (birthParts.length === 3) {
                captchaYear = parseInt(birthParts[0]);
                captchaMonth = parseInt(birthParts[1]);
                captchaDay = parseInt(birthParts[2]);
                
                $('#captcha-answer').val(captchaDay + '/' + captchaMonth + '/' + captchaYear);
            }
        } else {
            // Fallback: should not happen if admin configured properly
            captchaDay = 15;
            captchaMonth = 6;
            captchaYear = 1990;
            $('#captcha-answer').val(captchaDay + '/' + captchaMonth + '/' + captchaYear);
        }
        
        // Select random captcha type and render
        currentCaptchaType = getRandomCaptchaType();
        renderCaptcha();
        
        // Listen to form changes
        $('select[autocomplete="off"], input[name="payment-method"]').on('change', validateStep1);
        
        // Initial validation check
        validateStep1();
        
        // ============================================
        // Step 2 Captcha Initialization
        // ============================================
        
        // Variables for step 2 captcha (separate from step 1)
        let dayInputStep2 = '';
        let monthInputStep2 = '';
        let yearInputStep2 = '';
        let captchaDayStep2 = 10;
        let captchaMonthStep2 = 1;
        let captchaYearStep2 = 1985;
        let currentCaptchaTypeStep2 = 1;
        
        // Render captcha for step 2
        function renderCaptchaStep2() {
            const container = $('#birthdate-captcha-step2 > div');
            container.empty();
            
            const paddedDay = String(captchaDayStep2).padStart(2, '0');
            const paddedMonth = String(captchaMonthStep2).padStart(2, '0');
            
            switch(currentCaptchaTypeStep2) {
                case 1: // Day/Month/Year with shared numpad
                    container.html(`
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <div style="color: #495057; font-size: 13px; width: 100%;">
                                <p style="margin: 0; line-height: 1.5; width: 100%; font-weight: 500;">Doğum tarixinizi daxil edin:</p>
                            </div>
                            <div style="background: #fff; border: 2px solid #ced4da; border-radius: 4px; padding: 6px 12px; display: flex; gap: 5px; align-items: center; flex-shrink: 0;">
                                <span id="captcha-day-input-step2" style="color: #212529; font-size: 18px; min-width: 25px; text-align: center; font-weight: 600;">--</span>
                                <span style="color: #adb5bd;">/</span>
                                <span id="captcha-month-input-step2" style="color: #212529; font-size: 18px; min-width: 25px; text-align: center; font-weight: 600;">--</span>
                                <span style="color: #adb5bd;">/</span>
                                <span id="captcha-year-input-step2" style="color: #212529; font-size: 18px; min-width: 45px; text-align: center; font-weight: 600;">----</span>
                            </div>
                        </div>
                        <div style="max-width: 250px;">
                            <div id="shared-numpad-step2" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 4px;"></div>
                        </div>
                    `);
                    generateNumPad('#shared-numpad-step2', '#captcha-day-input-step2', 2, 'day');
                    break;
                
                case 2: // DD.MM.YYYY format with dots
                    container.html(`
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <div style="color: #495057; font-size: 13px; width: 100%;">
                                <p style="margin: 0; line-height: 1.5; font-weight: 600; color: #212529; width: 100%;">Doğum tarixinizi daxil edin:</p>
                            </div>
                            <div style="background: #fff; border: 2px solid #ced4da; border-radius: 4px; padding: 6px 12px; display: flex; gap: 5px; align-items: center; flex-shrink: 0;">
                                <span id="captcha-full-input-step2" style="color: #212529; font-size: 18px; min-width: 100px; text-align: center; font-weight: 600;">--.--.----</span>
                            </div>
                        </div>
                        <div style="max-width: 250px;">
                            <div id="full-numpad-step2" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 4px;"></div>
                        </div>
                    `);
                    generateNumPad('#full-numpad-step2', '#captcha-full-input-step2', 10, 'full');
                    break;
            }
            
            // Add error message div
            container.append('<div id="captcha-error-step2" style="color: #dc3545; font-size: 12px; margin-top: 15px; text-align: center; display: none;"></div>');
        }
        
        // Initialize step 2 captcha with user's birthdate
        if (typeof midaUserRestrictions !== 'undefined' && midaUserRestrictions.birthdate) {
            const birthParts = midaUserRestrictions.birthdate.split('-');
            if (birthParts.length === 3) {
                captchaYearStep2 = parseInt(birthParts[0]);
                captchaMonthStep2 = parseInt(birthParts[1]);
                captchaDayStep2 = parseInt(birthParts[2]);
                
                $('#captcha-answer-step2').val(captchaDayStep2 + '/' + captchaMonthStep2 + '/' + captchaYearStep2);
            }
        }
        
        // Select random captcha type and render for step 2
        currentCaptchaTypeStep2 = getRandomCaptchaType();
        renderCaptchaStep2();
        
        // Validate captcha for step 2
        window.validateCaptchaStep2 = function() {
            let userAnswer = '';
            const paddedDay = String(captchaDayStep2).padStart(2, '0');
            const paddedMonth = String(captchaMonthStep2).padStart(2, '0');
            const correctAnswer = paddedDay + paddedMonth + captchaYearStep2;
            
            switch(currentCaptchaTypeStep2) {
                case 1: // Day/Month/Year
                    const enteredDay = (window.dayInputStep2 || '').padStart(2, '0');
                    const enteredMonth = (window.monthInputStep2 || '').padStart(2, '0');
                    const enteredYear = window.yearInputStep2 || '';
                    
                    if (!window.dayInputStep2 || !window.monthInputStep2 || !window.yearInputStep2) {
                        $('#captcha-error-step2').css('color', '#dc3545').text('Bütün sahələri doldurun!').show();
                        return;
                    }
                    
                    userAnswer = enteredDay + enteredMonth + enteredYear;
                    break;
                    
                case 2: // DD.MM.YYYY format
                    userAnswer = $('#captcha-full-input-step2').text().replace(/\./g, '').replace(/-/g, '').trim();
                    
                    if (!userAnswer || userAnswer.length !== 8) {
                        $('#captcha-error-step2').css('color', '#dc3545').text('Tam tarixi daxil edin!').show();
                        return;
                    }
                    break;
            }
            
            const isCorrect = (userAnswer === correctAnswer);
            
            if (isCorrect) {
                console.log('=== Step 2 Captcha Success ===');
                $('#captcha-verified-step2').val('1');
                $('#captcha-error-step2').text('').hide();
                $('#birthdate-captcha-step2').css({
                    'border': '2px solid #28a745',
                    'background': '#d4edda'
                });
                
                $('#captcha-error-step2').css('color', '#28a745').text('✓ Təsdiqləndi!').show();
                
                // Update search button state
                if (typeof updateSearchButtonState === 'function') {
                    updateSearchButtonState();
                }
            } else {
                console.log('=== Step 2 Captcha Failed ===');
                $('#captcha-verified-step2').val('0');
                $('#captcha-error-step2').css('color', '#dc3545').text('Yanlış cavab!').show();
                
                // Reset inputs
                window.dayInputStep2 = '';
                window.monthInputStep2 = '';
                window.yearInputStep2 = '';
                $('#captcha-day-input-step2').text('--');
                $('#captcha-month-input-step2').text('--');
                $('#captcha-year-input-step2').text('----');
                $('#captcha-full-input-step2').text('--.--.----');
                
                // Reset numpad input type back to 'day' for type 1 captcha
                if (currentCaptchaTypeStep2 === 1) {
                    $('#shared-numpad-step2').find('.numpad-btn').data('input-type', 'day');
                    $('#shared-numpad-step2').find('[id$="-clear"]').data('input-type', 'day');
                    $('#shared-numpad-step2').find('[id$="-backspace"]').data('input-type', 'day');
                }
                
                $('#birthdate-captcha-step2').css({
                    'border': '2px solid #dc3545',
                    'background': '#f8d7da'
                });
                
                // Update search button state (disable it)
                if (typeof updateSearchButtonState === 'function') {
                    updateSearchButtonState();
                }
            }
        };
    });

})(jQuery);
