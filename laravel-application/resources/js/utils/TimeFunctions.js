export const twoDigits = (num) => {
    return String(num).padStart(2, '0')
}

export const getDate = (timestamp) => {
    if (timestamp === null || timestamp === undefined)
        return "---";

    let dateObj = new Date(timestamp);
    let month = dateObj.getUTCMonth() + 1;
    let day = dateObj.getUTCDate();
    let year = dateObj.getUTCFullYear();

    let newDate = twoDigits(day) + "/" + twoDigits(month) + "/" + year;

    return newDate;
}

export const getTime = (timestamp) => {
    if (timestamp === null || timestamp === undefined)
        return "---";

    let dateObj = new Date(timestamp);
    let hours = dateObj.getHours();
    let minutes = dateObj.getMinutes();

    let newTime = hours + ":" + twoDigits(minutes);

    return newTime;
}


export const getDifDatesInMilisec = (hora_entrada) => {
    let time1 = new Date(hora_entrada);
    let time2 = new Date();

    let dif = ( time2.getTime() - time1.getTime() );

    return dif;
}


export const isCurrentDay = (fechaParam) => {
    if (fechaParam === null)
        return false

    let time1 = new Date(fechaParam);
    let time2 = new Date();

    if (time1.toDateString() === time2.toDateString())
        return true;
    else
        return false;

  }


export const getDateToParam = (date) => {
    if (date !== null)
        return `${date.getUTCFullYear()}-${date.getUTCMonth() + 1}-${date.getUTCDate()}`
    else
        return "0";
}


export const formatHoursMinutes = (time) => {
    if (time !== null)
        return time.slice(0,-3)
    else
        return "-----";
}


export const decimalHoursToHoursMinutes = (decimalHours) => {
    if (decimalHours === undefined)
    return "";

    decimalHours = Number(decimalHours).toFixed(3);

    const hours = Math.trunc(decimalHours)

    const decimalHour = (Number(decimalHours).toFixed(2)).split(".");

    const minutes = decimalHour[1] * 60 / 100;

    return `${hours}h ${twoDigits(Math.floor(minutes))}m`;
}



export const readableDateMonthYear = (date) => {
    if (date === undefined)
    return "";

    const dateArray = date.split("-");

    const year = dateArray[0];
    const month = dateArray[1];
    const day = dateArray[2];

    const dateAux = new Date();
    dateAux.setMonth(month - 1);

    const monthName = dateAux.toLocaleString('es-ES', { month: 'short',});

    return `${monthName.toUpperCase()} ${year}`;
}


export const createDateFromString = (date) => {
    if (date === null || date === undefined)
    return null;

    let myDateFrom = new Date(date);

    let userTimezoneOffsetFrom = myDateFrom.getTimezoneOffset() * 60000;

    let finalDate = new Date(myDateFrom.getTime() + userTimezoneOffsetFrom);

    return finalDate;
}
