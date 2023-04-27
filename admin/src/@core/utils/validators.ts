import { isEmpty, isEmptyArray, isNullOrUndefined } from './index'

// 👉 Required Validator
export const requiredValidator = (value: unknown) => {
    const message = localStorage.getItem('app-language') === 'tr' ? 'Bu alan gereklidir' : 'This field is required'

    if (isNullOrUndefined(value) || isEmptyArray(value) || value === false)
        return message

    return !!String(value).trim().length || message
}

// 👉 Email Validator
export const emailValidator = (value: unknown) => {
    if (isEmpty(value))
        return true

    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/

    const message = localStorage.getItem('app-language') === 'tr' ? 'Email alanı geçerli bir email olmalıdır' : 'The Email field must be a valid email'

    if (Array.isArray(value))
        return value.every(val => re.test(String(val))) || message

    return re.test(String(value)) || message
}

// 👉 Password Validator
export const passwordValidator = (password: string) => {
    const regExp = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%&*()]).{8,}/

    const validPassword = regExp.test(password)

    const message = localStorage.getItem('app-language') === 'tr' ? '' : ''


    return (
        // eslint-disable-next-line operator-linebreak
        validPassword ||
        'Field must contain at least one uppercase, lowercase, special character and digit with min 8 chars'
    )
}

// 👉 Confirm Password Validator
export const confirmedValidator = (value: string, target: string) =>

    value === target || 'The Confirm Password field confirmation does not match'

// 👉 Between Validator
export const betweenValidator = (value: unknown, min: number, max: number) => {
    const valueAsNumber = Number(value)

    return (Number(min) <= valueAsNumber && Number(max) >= valueAsNumber) || `Enter number between ${min} and ${max}`
}

// 👉 Integer Validator
export const integerValidator = (value: unknown) => {
    if (isEmpty(value))
        return true

    const message = localStorage.getItem('app-language') === 'tr' ? 'Bu alan bir tamsayı olmalıdır' : 'This field must be an integer'

    if (Array.isArray(value))
        return value.every(val => /^-?[0-9]+$/.test(String(val))) || message

    return /^-?[0-9]+$/.test(String(value)) || message
}

// 👉 Regex Validator
export const regexValidator = (value: unknown, regex: RegExp | string): string | boolean => {
    if (isEmpty(value))
        return true

    let regeX = regex
    if (typeof regeX === 'string')
        regeX = new RegExp(regeX)

    if (Array.isArray(value))
        return value.every(val => regexValidator(val, regeX))

    return regeX.test(String(value)) || 'The Regex field format is invalid'
}

// 👉 Alpha Validator
export const alphaValidator = (value: unknown) => {
    if (isEmpty(value))
        return true

    const message = localStorage.getItem('app-language') === 'tr' ? 'Yalnızca alfabetik karakterler içerebilir' : 'The Alpha field may only contain alphabetic characters'

    return /^[a-zA-ZğüşöçİĞÜŞÖÇ]+$/i.test(String(value)) || message
}

// 👉 URL Validator
export const urlValidator = (value: unknown) => {
    if (isEmpty(value))
        return true

    const re = /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/

    return re.test(String(value)) || 'URL is invalid'
}

// 👉 Length Validator
export const lengthValidator = (value: unknown, length: number) => {
    if (isEmpty(value))
        return true

    return String(value).length === length || `The Min Character field must be at least ${length} characters`
}

// 👉 Length Between Validator
export const lengthMaxValidator = (value: string, length: number) => {
    if (isEmpty(value))
        return true
    const message = localStorage.getItem('app-language') === 'tr' ? `Maksimum Karakter alanı en az ${length} karakter olmalıdır` : `The Max Character field must be at least ${length} characters`
    return (Number(length) >= value.length) || message
}


// 👉 Length Between Validator
export const lengthBetweenValidator = (value: string, min: number, max: number) => {
    if (isEmpty(value))
        return true
    const message = localStorage.getItem('app-language') === 'tr' ? `${min} ile ${max} arasında bir karakter girin` : `Enter character between ${min} and ${max}`
    return (Number(min) <= value.length && Number(max) >= value.length) || message
}

// 👉 Alpha-dash Validator
export const alphaDashValidator = (value: unknown) => {
    if (isEmpty(value))
        return true

    const valueAsString = String(value)

    const message = localStorage.getItem('app-language') === 'tr' ? 'Tüm Karakterler geçerli değil' : 'All Character are not valid'


    return /^[0-9A-Z_-]*$/i.test(valueAsString) || message
}