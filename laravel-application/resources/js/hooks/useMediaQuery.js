/*
    Documentación del codigo de abajo

    https://samuelkraft.com/blog/responsive-animation-framer-motion

    Este código se uso para hacer animaciones responsivas
*/

import { useState, useEffect } from 'react'

const useMediaQuery = (query) => { 
    const [matches, setMatches] = useState(false);

    useEffect(() => {
        const media = window.matchMedia(query);
        if (media.matches !== matches) {
            setMatches(media.matches);
        }
        const listener = () => {
            setMatches(media.matches);
        };
        media.addEventListener("change", listener);
        return () => media.removeEventListener("change", listener);
    }, [matches, query]);

    return matches;
}

export default useMediaQuery

//Breakpoints de Bootstrap
export const useIsViewportSmall = () => useMediaQuery('(min-width: 576px)');
export const useIsViewportMedium = () => useMediaQuery('(min-width: 768px)');
export const useIsViewportLarge = () => useMediaQuery('(min-width: 992px)');
export const useIsViewportExtraLarge = () => useMediaQuery('(min-width: 1200px)');
export const useIsViewportExtraExtraLarge = () => useMediaQuery('(min-width: 1400px)');