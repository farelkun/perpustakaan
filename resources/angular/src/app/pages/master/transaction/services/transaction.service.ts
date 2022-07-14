import { Injectable } from '@angular/core';
import { LandaService } from 'src/app/core/services/landa.service';

@Injectable({
    providedIn: 'root'
})
export class TransactionService {

    constructor(private landaService: LandaService) { }

    getTransactions(arrParameter) {
        return this.landaService.DataGet('/v1/transactions', arrParameter);
    }

    getTransactionById(transactionId) {
        return this.landaService.DataGet('/v1/transactions/' + transactionId);
    }

    createTransaction(payload) {
        return this.landaService.DataPost('/v1/transactions', payload);
    }

    updateTransaction(payload) {
        return this.landaService.DataPut('/v1/transactions', payload);
    }

    deleteTransaction(transactionId) {
        return this.landaService.DataDelete('/v1/transactions/' + transactionId);
    }
}
