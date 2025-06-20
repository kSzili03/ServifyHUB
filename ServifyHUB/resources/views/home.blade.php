@extends('layouts.default') <!-- Extends the default layout for the page -->

@section('title', 'Home') <!-- Sets the title for the home page -->

<style>
    /* Category Carousel */
    .category-card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .category-card:hover {
        transform: translateY(-8px); /* Moves the category card up slightly on hover */
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.15); /* Increases shadow on hover */
    }

    .category-img {
        width: 100%; /* Ensures the image spans the entire width */
        max-height: 600px; /* Limits image height */
        object-fit: cover; /* Ensures image covers the container without stretching */
    }

    .category-card-body {
        padding: 10px;
        text-align: center;
    }

    .category-title {
        font-size: 1rem;
        font-weight: 600;
        color: #333;
    }

    .category-card a {
        text-decoration: none;
        color: #ff8c00;
        font-weight: bold;
    }

    .category-card a:hover {
        color: #ff6a00; /* Changes color when hovering over category link */
    }

    /* Carousel Styles */
    .carousel-inner {
        border-radius: 12px;
    }

    .carousel-caption {
        position: absolute;
        bottom: 15px;
        left: 10px;
        right: 10px;
        padding: 10px;
        background: rgba(0, 0, 0, 0.5);  /* Dark background for better text readability */
        border-radius: 8px;
    }

    .carousel-caption h5 {
        font-size: 1.2rem;
        color: #fff;
        white-space: normal;
        word-wrap: break-word;
        margin: 0;
    }

    .carousel-caption p {
        font-size: 0.8rem;
        color: #fff;
        white-space: normal;
        word-wrap: break-word;
        margin-top: 5px;
    }

    /* Service Cards */
    .service-card {
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease-in-out;
    }

    .service-card:hover {
        transform: translateY(-6px); /* Moves the service card up on hover */
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15); /* Increases shadow on hover */
    }

    .service-card-img {
        width: 100%;
        height: 200px; /* Fixed height for service card images */
        object-fit: cover;
        border-radius: 10px;
    }

    .service-card-text {
        font-size: 0.95rem;
        font-weight: 600;
        color: #333;
    }

    /* Section Titles */
    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1.2rem;
        color: #333;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .service-card-img {
            height: 180px;  /* Slightly smaller images for medium screens */
        }

        .category-img {
            max-height: 150px; /* Smaller images for smaller screens */
        }
    }

    @media (max-width: 576px) {
        .service-card-img {
            height: 160px;  /* Smaller images for extra small screens */
        }

        .category-img {
            height: 120px; /* Smaller images for mobile */
        }

        .carousel-caption h5 {
            font-size: 1.1rem; /* Adjusted text size for mobile */
        }

        .carousel-caption p {
            font-size: 0.7rem; /* Adjusted text size for mobile */
        }
    }

</style>

@section('content')
    <div class="container mt-5">

        <!-- Featured Categories Carousel Section -->
        <div id="categoryCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
            <div class="carousel-inner">
                @foreach($categories as $index => $category)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <div class="card category-card">
                            @php
                                $categoryImage = 'https://via.placeholder.com/500x300.png?text='.$category->name;

                                switch ($category->name) {
                                    case 'IT':
                                        $categoryImage = asset('storage/categories/it.jpg');
                                        break;
                                    case 'Building':
                                        $categoryImage = asset('storage/categories/building.jpg');
                                        break;
                                    case 'Education':
                                        $categoryImage = asset('storage/categories/education.jpg');
                                        break;
                                    case 'Beauty Care':
                                        $categoryImage = asset('storage/categories/beauty.jpg');
                                        break;
                                    case 'Woodworking':
                                        $categoryImage = asset('storage/categories/woodworking.jpg');
                                        break;
                                    case 'Entertainment':
                                        $categoryImage = asset('storage/categories/entertainment.jpg');
                                        break;
                                    case 'Culture and Community':
                                        $categoryImage = asset('storage/categories/community.jpg');
                                        break;
                                    case 'Business and Finance':
                                        $categoryImage = asset('storage/categories/business.jpg');
                                        break;
                                    case 'Travel':
                                        $categoryImage = asset('storage/categories/travel.jpg');
                                        break;
                                    case 'Health and Wellness':
                                        $categoryImage = asset('storage/categories/health.jpg');
                                        break;
                                    case 'Technology':
                                        $categoryImage = asset('storage/categories/technology.jpg');
                                        break;
                                    case 'Home Improvement':
                                        $categoryImage = asset('storage/categories/home.jpg');
                                        break;
                                    case 'Food and Beverage':
                                        $categoryImage = asset('storage/categories/food.jpg');
                                        break;
                                    case 'Sports':
                                        $categoryImage = asset('storage/categories/sport.jpg');
                                        break;
                                    case 'Automotive':
                                        $categoryImage = asset('storage/categories/automotive.jpg');
                                        break;
                                    case 'Fashion':
                                        $categoryImage = asset('storage/categories/fashion.jpg');
                                        break;
                                    case 'Real Estate':
                                        $categoryImage = asset('storage/categories/estate.jpg');
                                        break;
                                    case 'Arts and Crafts':
                                        $categoryImage = asset('storage/categories/art.jpg');
                                        break;
                                    case 'Music':
                                        $categoryImage = asset('storage/categories/music.jpg');
                                        break;
                                    case 'Legal Services':
                                        $categoryImage = asset('storage/categories/legal.jpg');
                                        break;
                                    case 'Marketing':
                                        $categoryImage = asset('storage/categories/marketing.jpg');
                                        break;
                                    case 'Fitness':
                                        $categoryImage = asset('storage/categories/fitness.jpg');
                                        break;
                                    default:
                                        $categoryImage = asset('storage/placeholder.svg');
                                        break;
                                }
                            @endphp

                                <!-- Category Image and Title -->
                            <a href="{{ route('services.all', ['category' => $category->id]) }}">
                                <img src="{{ $categoryImage }}" class="category-img img-fluid" width="150" height="150" alt="{{ $category->name }}">
                            </a>
                            <div class="card-body category-card-body">
                                <h5 class="category-title">
                                    <a href="{{ route('services.all', ['category' => $category->id]) }}" class="service-card-text">
                                        {{ $category->name }}
                                    </a>
                                </h5>
                                <p>Explore the best services under {{ $category->name }}.</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#categoryCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#categoryCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <!-- Carousel for Top Viewed Services -->
        <h3 class="section-title mt-5">Top Viewed Services</h3>
        <div id="topViewedCarousel" class="carousel slide mt-5" data-bs-ride="carousel" data-bs-interval="3000">
            <div class="carousel-inner">
                @foreach($topViewedServices as $index => $service)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <a href="{{ route('services.show', $service->id) }}">
                            <img src="{{ $service->service_picture ? asset('storage/'.$service->service_picture) : asset('storage/placeholder.svg') }}" class="d-block w-100 top-viewed-carousel-img" alt="{{ $service->name }}">
                            <div class="carousel-caption d-block">
                                <h5 class="text-truncate">{{ $service->name }}</h5>
                                <p class="text-truncate">{{ $service->description }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#topViewedCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#topViewedCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <!-- Latest Services Section -->
        <h3 class="section-title mt-5">Latest Services</h3>
        @if($latestServices->isEmpty())
            <p>No services available at the moment.</p>
        @else
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($latestServices as $service)
                    <div class="col">
                        <div class="card service-card text-dark bg-light bg-gradient h-100" style="border-radius: 12px">
                            <a href="{{ route('services.show', $service->id) }}">
                                <img src="{{ $service->service_picture ? asset('storage/'.$service->service_picture) : asset('storage/placeholder.svg') }}" class="service-card-img card-img-top" alt="{{ $service->name }}">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="{{ route('services.show', $service->id) }}" class="service-card-text" style="text-decoration: none;">
                                        {{ $service->name }}
                                    </a>
                                </h5>
                                <p>{{ $service->description }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
