let pointerNumber = 0;
let totalCardsShown = 0;
$mo =jQuery;

$mo(window).load( function() {
    if(!moTour.tourTaken) {
        startTour(pointerNumber);
    }
    /** Restart tour for current page when clicked on the Restart button */
    $mo("#restart_tour_button").click( function() {
        resetTour();
        tourComplete();
        if(moTour.tourData.length <= 0) {
            return;
        }
        startTour(pointerNumber);
     });
});

/**
 * This function calls the functions that add overlay and create the cards.
 * @param pointerNumber int value
 */
function startTour(pointerNumber){
    if(!moTour.tourData) return;
    $mo("#moblock").show();
    // $mo("#moblock").css('animation', 'fadeOut 500ms');
    createCard(pointerNumber);
}

/**
 * This function creates the cards and adds them on a calculated position
 * @param pointerNumber
 */
function createCard(pointerNumber) {
    let tourElement =   moTour.tourData[pointerNumber];
    if(!tourElement) {
        abruptEnd();
        return;
    }
    let card        =   '<div id="mo-card" class="mo-card mo-'+tourElement.cardSize+'">'+
                            '<div id="mo-card-arrow" class="mo-tour-arrow mo-point-'+tourElement.pointToSide+'">';
                            if(!tourElement.img.includes("replay.svg")) {
                                card += '<em id="mo-card-arrow-img" style="color:#ffffff;position: relative;" ' +
                                    'class=" dashicons dashicons-arrow-'+tourElement.pointToSide+'"></em>';
                            }
                            card += '</div>'+
                            '<div id="mo-card-content" class="mo-tour-content-area mo-point-'+tourElement.pointToSide+'">'+
                                '<div class="mo-tour-title"><span id="mo-card-content-title">'+tourElement.titleHTML+'</span></div>'+
                                '<div class="mo-tour-content"><span id="mo-card-content-html">'+tourElement.contentHTML+'</span></div>'+
                                '<img id="mo-card-content-img" '+(tourElement.img ? '':'hidden')+' src="'+tourElement.img+'" alt=""> '+
                                '<div class="mo-tour-button-area"></div>'
                                +'<div hidden class="mo-tour-card-bottom"></div>' +
                            '</div>' +
                        '</div>';
    let nextChecker =   tourElement.buttonText === "false" || tourElement.buttonText === '' ? false : true;
    let nextButton  =   '<input id="mo-card-nextButton" type="button"  class="mo-tour-button mo-tour-primary-btn" value="'+tourElement.buttonText+'">';
    let skipButton  =   '<input type="button" id="skippy" class="mo-tour-button mo-skip-btn" value="Skip Tour">';

    $mo("#moblock").empty();
    $mo(card).insertAfter('#moblock');
    // .animate({
    //     opacity: 1
    // }, 500);

    $mo('.mo-tour-button-area').append(skipButton);
    if(nextChecker) {
        $mo('.mo-tour-button-area').append(nextButton); //Will keep true always
    } else {
        $mo('#skippy').val('Close'); // skip a completed tour?
    }

    // Emphasised shadow When not pointing to any element and placed in the center
    if(tourElement.pointToSide=='' || tourElement.pointToSide=='center') {
        $mo('.mo-card').attr('style', 'box-shadow:0px 0px 0px 3px #979393');
    }

    setInterval(function() {
        $mo("#mo-card").css('opacity', 1);
        $mo("#mo-card").css('transform', 'scale(1)');
    }, 100);
    

    // On Next button clicked, create and display next card if exist else Save tour option
    $mo('.mo-tour-primary-btn').click( function() {
        $mo('.mo-target-index').removeClass('mo-target-index');
            pointerNumber+=1;
            if(moTour.tourData[pointerNumber]) {
                changeCard(pointerNumber);
            } else {
                $mo('.mo-card').remove();
                resetTour();
                tourComplete();
            }
    });

    // On Skip button click, Reset the tour and remove the overlay and existing card.
    $mo(".mo-skip-btn").click( function() {
        $mo('.mo-target-index').removeClass('mo-target-index');
        $mo('.mo-card').remove();
        $mo('html, body').animate({
            scrollTop: 0
        }, 300);
        resetTour();
        tourComplete();
    });

    handleCardInteractions(pointerNumber);

}

/**
 * Function to handle human interactions.
 * @param {any} pointerNumber 
 * @param {any} card 
 */
function handleCardInteractions(pointerNumber) {
    let tourElement =   moTour.tourData[pointerNumber];
    // When poiniting to any element, calculate the position of the card
    if(tourElement.targetE) {
        getPointerPosition(tourElement.targetE, tourElement.pointToSide);
    }
}
/**
 * This function calculates the Top and Left position for the card to be added
 * w.r.t. the target element. getBoundingClientRect() function returns top, bottom, left,
 * right, height and width of an element.
 * @param targetE     -   Target element to which card points
 * @param pointToSide -   The direction to which card points
 */
function getPointerPosition(targetE,pointToSide) {
    let target = document.getElementById(targetE);
    if(!target) {
        abruptEnd();
        return;
    }
    let targetDimentions = target.getBoundingClientRect();
    let cardDimentions   = document.getElementById('mo-card').getBoundingClientRect();
    let finalLeft,finalTop;

    switch(pointToSide) {
        case 'up'    :
            finalLeft   =   targetDimentions.left + (targetDimentions.width - cardDimentions.width)/2 ;
            finalTop    =   targetDimentions.top + targetDimentions.height + 5;
            break;
        case 'down'  :
            finalLeft   =   targetDimentions.left + (targetDimentions.width - cardDimentions.width)/2 ;
            finalTop    =   targetDimentions.top - cardDimentions.height ;
            break;
        case 'left'  :
            finalLeft   =   targetDimentions.left + targetDimentions.width;
            finalTop    =   targetDimentions.top + (targetDimentions.height - cardDimentions.height)/2 ;
            break;
        case 'right' :
            finalLeft   =   targetDimentions.left - cardDimentions.width;
            finalTop    =   targetDimentions.top + (targetDimentions.height - cardDimentions.height)/2 ;
            break;

    }
    // To adjust if card goes out of screen
    if(finalTop<0)  {
        $mo('.mo-tour-arrow>i').css('top','calc(50% + '+finalTop+'px)');
        finalTop=0;
    }

    // Return if element is on screen.
    if(finalTop<=0 && finalLeft<=0) {
        abruptEnd();
        return;
    }
    //Adding the calculated position to the card as css property
    $mo('.mo-card').css({
        'top':(finalTop+$mo(window).scrollTop()-25),
        'left':(finalLeft+$mo(window).scrollLeft()-180),
        'margin-top':'0','margin-left':'0','position':'absolute'
    });
    if(pointToSide === 'up' || pointToSide === 'down') {
        $mo("#mo-card").height(function(index,currentheight) {
            return currentheight+10;
        });
    }
    // To get the target Element over the Overlay and highlight
    $mo('#'+targetE).addClass('mo-target-index');
    // Scroll to the target element

    document.getElementById(targetE).scrollIntoView({
        behavior: 'smooth',
        block: 'center',
        inline: 'center'
    });

}

/**
 * Removes the backdrop i.e. the overlay and cards, Resets the pointer number to zero
 */
function resetTour() {
    pointerNumber=0;
    $mo('#moblock').fadeOut('2000');
    $mo('#moblock').hide();
}

/**
 * When the last element is reached, save a tourTaken variable in BD.
 * So that the tour doesn't start automatically next time.
 */
function tourComplete() {
    $mo.ajax({
        url: moTour.siteURL,
        type: "POST",
        data: {
            doneTour    :true,
            pageID      :moTour.pageID,
            security    :moTour.tnonce,
            action      :moTour.ajaxAction,
        },
        crossDomain: !0,dataType: "json",
    });
}

/**
 * Function to end tour due to errors
 */
function abruptEnd() {
    $mo('.mo-card').remove();
    resetTour();
    tourComplete();
    return;
}

/**
 * Change card content.
 */
function changeCard(pointerNumber) {
    let tourElement =   moTour.tourData[pointerNumber];
    removeCardCss();
    $mo("#mo-card").addClass('mo-'+tourElement.cardSize)
    $mo("#mo-card-arrow").addClass("mo-point-"+tourElement.pointToSide);
    $mo("#mo-card-arrow-img").addClass("dashicons-arrow-"+tourElement.pointToSide);
    if(tourElement.img.includes("replay.svg")) {
        console.log("matches");
        $mo("#mo-card-arrow").hide();
        $mo("#mo-card-arrow-img").hide();
    } else {
        console.log("nomatches");
        console.log(tourElement.img);
        $mo("#mo-card-arrow").show();
        $mo("#mo-card-arrow-img").show();
    }
    $mo("#mo-card-content").addClass("mo-point-"+tourElement.pointToSide);
    $mo("#mo-card-content-title").html(tourElement.titleHTML);
    $mo("#mo-card-content-html").html(tourElement.contentHTML);
    if (tourElement.img) {
        $mo("#mo-card-content-img").hide();
        $mo("#mo-card-content-img").attr("src", tourElement.img);
        $mo("#mo-card-content-img").show();
    } else {
        $mo("#mo-card-content-img").hide();
    }
    let nextChecker =   tourElement.buttonText === "false" || tourElement.buttonText === '' ? false : true;
    if(!nextChecker) {
        $mo('#skippy').val('Close'); // skip a completed tour?
        $mo('#mo-card-nextButton').remove();
    } else {
        $mo('#mo-card-nextButton').val(tourElement.buttonText);
    }
    
    // Emphasised shadow When not pointing to any element and placed in the center
    if(tourElement.pointToSide=='' || tourElement.pointToSide=='center') {
        $mo('.mo-card').attr('style', 'box-shadow:0px 0px 0px 3px #979393');
    }
    handleCardInteractions(pointerNumber);

}

function removeCardCss() {
    $mo("#mo-card").removeClass("mo-big");
    $mo("#mo-card").removeClass("mo-small");
    $mo("#mo-card").removeClass("mo-medium");
    removeClassStartingWith($mo("#mo-card-arrow"), "mo-point-");
    removeClassStartingWith($mo("#mo-card-arrow-img"), "dashicons-arrow-");
    removeClassStartingWith($mo("#mo-card-content"), "mo-point-");
    $mo("#mo-card").removeAttr("style");
}

function removeClassStartingWith(node, begin) {
    node.removeClass (function (index, className) {
        return (className.match ( new RegExp("\\b"+begin+"\\S+", "g") ) || []).join(' ');
    });
}