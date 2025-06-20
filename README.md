## Introduction

This project is a comprehensive web application that efficiently, reliably, and user-friendly connects users with local service providers. The platform enables browsing service provider profiles, submitting reviews, viewing reference portfolios, managing services, and private communication with providers.

---

## Purpose and Economic Impact

The platform supports small and medium-sized enterprises by increasing their online visibility and credibility through user-generated reviews and portfolios. It encourages users to make informed decisions based on genuine feedback and direct communication, promoting transparency and trust in the local service market. This contributes to economic growth by helping local providers reach more customers and build their reputation.

---

## User Requirements

- Users must create an account to access personalized features such as submitting reviews, writing comments, or sending messages.

- Service providers must manage their profiles, list and update offered services, upload reference images, and maintain contact with customers.

- The system must prevent spam and abuse by allowing only verified users to submit reviews, comments, or messages.

- All interactions should be intuitive, fast, and secure.

---

## Technologies Used

- **Backend:** Laravel PHP framework, providing MVC architecture and reliable database management.

- **Frontend:** Blade templating system with Bootstrap for a responsive and clean user interface.

- **Database:** MySQL for relational data management.

- **Authentication:** Laravel's built-in user authentication (login, registration, password reset).

- **JavaScript:** For dynamic content updates enhancing user experience.

- **Version Control:** GitHub for source code management and project hosting.

---

## Features Overview

### 1. Homepage Overview  

A clean and organized homepage that helps users quickly find services. Features include:

- **Category Carousel:** A visually appealing carousel showcasing service categories. Users can click on categories to browse services within that category.

- **Most Viewed Services:** A dynamically updated list of the most popular services based on visits.

- **Latest Services:** Displays the most recently added or updated services.

![Home Page](https://github.com/user-attachments/assets/a64d4ed5-db9d-4c06-92b2-8ee60abf5f66)
![Home Page Carousels](https://github.com/user-attachments/assets/bb4465af-5641-4c91-8930-1b4663470501)

---

### 2. Service Search Page  

The Service Search page offers multiple filtering and sorting options:

- **Search Bar:** Keyword-based search (e.g., “plumber”, “cleaning”, “gardening”).

- **Filters:** Services can be filtered by category, price, location, and other parameters.

- **Sorting Options:** Results can be sorted alphabetically, by price, or popularity in ascending or descending order.

- **Results:** A list of services with title, prices, and locations.

![Search Services](https://github.com/user-attachments/assets/776472b7-3044-4060-8dad-a380fbbac450)

---

### 3. User Authentication: Login and Registration  

Users can register or login to access personalized services.

- Login with “Remember Me” functionality.

- Registration includes validation (username, email, password, confirm password).

- Authenticated users can create services, submit reviews, upload references, write comments, and send messages.

---

### 4. Profile Management  

Profiles are publicly viewable by others.

Users can edit personal information, passwords, profile pictures, contact details, and other data.

![Profile View](https://github.com/user-attachments/assets/d51be899-aa46-4fa1-9270-132287c42136)

---

### 5. Service Management  

Users can add, update, or delete their services.

Services are displayed on the user's public profile.

Each service includes a name, description, category, subcategory, price, contact, location.

![Create Services Form](https://github.com/user-attachments/assets/069c53d5-ff36-4abf-939a-bc8313983a41)
![Service With Details](https://github.com/user-attachments/assets/da2eec08-1c87-4ca3-89fe-61ad13cf9976)

---

### 6. User Reviews  

Registered users can submit a rating with stars and a text comment per service provider. The average rating is displayed on the provider's profile.

Reviews increase transparency and help potential customers make informed decisions.

Reviews cannot be edited after submission, ensuring reliability.

![Reviews](https://github.com/user-attachments/assets/37e237f6-4990-4925-82af-5b6d36135cce)

---

### 7. Reference Images 

Providers can showcase their work by uploading images with brief descriptions.

Images are displayed in a responsive gallery. Clicking opens a modal window with detailed information. Providers can edit or delete their references.

![References View](https://github.com/user-attachments/assets/6221b08c-48ef-4cf9-8399-ee6e7ae91062)
![Add Reference Form](https://github.com/user-attachments/assets/ad553375-d5b1-4282-8470-748fa99c3c02)

---

### 8. Private Messaging  

Users and providers can communicate directly through a private messaging system.

Conversations can be initiated from profiles or service detail pages. All conversations are accessible from a message list view.

Messages can be edited or deleted by the author.

![Contact Form](https://github.com/user-attachments/assets/f95763ed-3341-4fbe-95cb-6489955d1e2a)
![Conversation Messages](https://github.com/user-attachments/assets/b301c9ec-5580-40b6-b33b-2c8ee1224698)

---

## Summary

This platform supports the local economy by connecting providers and customers through transparent reviews, impressive portfolios, and direct communication. It offers a scalable, secure, and user-friendly solution for both parties.