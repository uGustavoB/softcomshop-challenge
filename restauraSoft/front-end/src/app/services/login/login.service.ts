import { Injectable } from '@angular/core';
import { environment } from '../../../environments/environment';
import { tap, catchError, throwError } from 'rxjs';
import { ApiService } from '../API/api.service';

interface LoginResponse {
  name: string;
  token: string;
}

@Injectable({
  providedIn: 'root'
})
export class LoginService {

  private apiUrl = `${environment.apiUrl}/auth`;

  constructor(private api: ApiService) {}

  login(email: string, password: string) {
    return this.api.post<LoginResponse>(`${this.apiUrl}/login`, { email, password }).pipe(
      tap(response => {
        // @ts-ignore
        const token = response.token;
        if (token) {
          localStorage.setItem('token', token);
        }
      }),
      catchError(error => {
        console.error('Login failed:', error);
        return throwError(() => error);
      })
    );
  }

  register(name: string, email: string, password: string) {
    return this.api.post<LoginResponse>(`${this.apiUrl}/register`, { name, email, password }).pipe(
      tap(response => {
        // @ts-ignore
        const token = response.token;
        if (token) {
          localStorage.setItem('token', token);
        }
      }),
      catchError(error => {
        console.error('Registration failed:', error);
        return throwError(() => error);
      })
    );
  }
}
