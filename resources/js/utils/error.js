// import { DEBUG } from '../config/constants';

export class AppError extends Error {
    constructor(message, code = 'UNKNOWN_ERROR', details = null) {
        super(message);
        this.name = 'AppError';
        this.code = code;
        this.details = details;
        this.timestamp = new Date();
    }
}

export function handleError(error, context = '') {
    if (DEBUG) {
        console.error(`[${context}] Error:`, error);
    }

    return error;
}

export function logWarning(message, context = '') {
    if (DEBUG) {
        console.warn(`[${context}] Warning:`, message);
    }
}

export function logInfo(message, context = '') {
    if (DEBUG) {
        console.log(`[${context}] Info:`, message);
    }
}
