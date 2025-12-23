import {Component, OnInit} from '@angular/core';
import {NavigationEnd, Router, RouterModule} from '@angular/router';
import {filter} from 'rxjs';
import {LoginService} from '../../../services/login/login.service';
import {MatIconModule} from '@angular/material/icon';
import {CommonModule} from '@angular/common';

interface MenuItem {
  href: string;
  label: string;
  icon: string;
}

@Component({
  selector: 'app-sidebar',
  imports: [
    MatIconModule,
    RouterModule,
    CommonModule
  ],
  templateUrl: './sidebar.html',
  styleUrl: './sidebar.css',
})
export class Sidebar implements OnInit{
  isSidebarOpen = false;
  currentPath: string = '';

  menuItems: MenuItem[] = [
    { href: '/pedidos', label: 'Pedidos', icon: 'shopping_bag' },
    { href: '/mesas', label: 'Mesas', icon: 'table_restaurant' },
    { href: '/pratos', label: 'Pratos', icon: 'restaurant' },
    { href: '/categorias', label: 'Categorias', icon: 'category' },
  ];

  constructor(
    public loginService: LoginService,
    private router: Router
  ) {}

  ngOnInit(): void {
    // Destacar rota atual ao navegar
    this.router.events
      .pipe(filter(event => event instanceof NavigationEnd))
      .subscribe((event: any) => {
        this.currentPath = event.url;
      });

    this.currentPath = this.router.url;
  }

  toggleSidebar(): void {
    this.isSidebarOpen = !this.isSidebarOpen;
  }

  closeSidebar(): void {
    this.isSidebarOpen = false;
  }

  isActive(path: string): boolean {
    return this.currentPath === path;
  }

  logout(): void {
    this.loginService.logout();
  }

  getUserInitial(): string {
    return this.loginService.currentUser?.name?.charAt(0).toUpperCase() || 'U';
  }

  navigateTo(path: string): void {
    this.router.navigate([path]);
    this.closeSidebar();
  }
}
