import {Injectable} from '@angular/core';
import {BehaviorSubject, catchError, tap, throwError} from 'rxjs';
import {ApiService} from '../API/api.service';
import {Router} from '@angular/router';

interface User {
  id: number;
  name: string;
  email: string;
}

interface LoginResponse {
  user: any;
  // outras propriedades se necessário
}

@Injectable({
  providedIn: 'root'
})
export class LoginService {
  private userSubject = new BehaviorSubject<User | null>(null);
  user$ = this.userSubject.asObservable();

  constructor(
    private api: ApiService,
    private router: Router
  ) {
    this.loadUserFromStorage();
  }

  login(email: string, password: string) {
    return this.api.post<LoginResponse>(`login`, { email, password }).pipe(
      tap(response => {
        console.log('Login successful:', response);
      }),
      catchError(error => {
        console.error('Login failed:', error);
        return throwError(() => error);
      })
    );
  }

  register(name: string, email: string, password: string) {
    return this.api.post<LoginResponse>(`register`, { name, email, password }).pipe(
      tap(response => {

        console.log('Registration successful:', response);
      }),
      catchError(error => {
        console.error('Registration failed:', error);
        return throwError(() => error);
      })
    );
  }

  logout() {
    localStorage.removeItem('token');
    this.router.navigate(['/login']);
  }

  get currentUser(): User | null {
    return this.userSubject.value;
  }

  // Carregar usuário do localStorage
  private loadUserFromStorage(): void {
    const userData = localStorage.getItem('user');
    if (userData) {
      try {
        const user = JSON.parse(userData);
        this.userSubject.next(user);
      } catch (error) {
        console.error('Erro ao parsear usuário do localStorage:', error);
      }
    }
  }
}
