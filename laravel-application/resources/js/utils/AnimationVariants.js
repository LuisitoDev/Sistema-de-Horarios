export const variantsWebFade = {
    fadeIn: {
        opacity: 1,
        transition: {
            ease: "easeInOut", 
            duration: .5
        }
    },
    fadeOut: {
        opacity: 0,
        transition: {
            ease: "easeInOut", 
            duration: .3
        }
    },
};

export const variantsMobileSwipe = {
    swipeIn: {
        x: "0vw",
        position: "static",
        transition: {
            ease: "easeInOut", 
            duration: .5
        }
    },
    swipeOutToLeft: {
        x: "-100vw", 
        position: "absolute",
        transition: {
            ease: "easeInOut", 
            duration: .5
        }
    },
    swipeOutToRight: {
        x: "100vw", 
        position: "absolute",
        transition: {
            ease: "easeInOut", 
            duration: .5
        }
    },
}