import { NOTIFICATION_DURATIONS } from '../config/constants';
import { logWarning } from '../utils/errorHandler';

export default class NotificationService {
    constructor(notifyInstance) {
        if (!notifyInstance) {
            logWarning('NotificationService: No notify instance provided', 'NotificationService');
            return;
        }

        this._notify = notifyInstance;
        this._durations = NOTIFICATION_DURATIONS;
    }

    success(message, duration = this._durations.SUCCESS) {
        if (this._notify && this._notify.Success) {
            this._notify.Success(message, duration);
        }
    }

    warning(message, duration = this._durations.WARNING) {
        if (this._notify && this._notify.Warning) {
            this._notify.Warning(message, duration);
        }
    }

    error(message, duration = this._durations.ERROR) {
        if (this._notify && this._notify.Error) {
            this._notify.Error(message, duration);
        }
    }

    // Cart-specific notifications
    addedToCart(duration = this._durations.SUCCESS) {
        this.success('Agregado al carrito', duration);
    }

    cartError(duration = this._durations.ERROR) {
        this.error('Ha ocurrido un error al agregar al carrito', duration);
    }

    insufficientStock(duration = this._durations.WARNING) {
        this.warning('No hay suficiente stock disponible.', duration);
    }

    invalidQuantity(duration = this._durations.WARNING) {
        this.warning('Debe agregar al menos una unidad', duration);
    }

    emptyCart(duration = this._durations.WARNING) {
        this.warning('Su carrito está vacío.', duration);
    }
}
