from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager
import time

# Setup the Chrome driver
options = webdriver.ChromeOptions()
options.add_argument('--headless')  # Run in headless mode
options.add_argument('--no-sandbox')
options.add_argument('--disable-dev-shm-usage')
driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()), options=options)

# Function to get reviews from a single page
def get_reviews_from_page(driver, url):
    driver.get(url)
    time.sleep(2)  # Allow time for the page to load

    reviews = []
    review_elements = driver.find_elements(By.XPATH, '//div[@data-hook="review"]')

    for review_element in review_elements:
        try:
            rating =  review_element.find_element(By.XPATH, './/i[@data-hook="review-star-rating"]').text
        except:
            rating = None
        try:
            title = review_element.find_element(By.XPATH, './/a[@data-hook="review-title"]').text
        except:
            title = None
        try:
            content = review_element.find_element(By.XPATH, './/span[@data-hook="review-body"]').text
        except:
            content = None
        try:
            date = review_element.find_element(By.XPATH, './/span[@data-hook="review-date"]').text
        except:
            date = None
        try:
            reviewer = review_element.find_element(By.XPATH, './/span[@class="a-profile-name"]').text
        except:
            reviewer = None

        reviews.append({
            'rating': rating,
            'title': title,
            'content': content,
            'date': date,
            'reviewer': reviewer
        })

    return reviews

# Get all reviews from multiple pages
def get_all_reviews(asin, max_pages=5):
    all_reviews = []
    for page_number in range(1, max_pages + 1):
        url = f"https://www.amazon.in/product-reviews/{asin}/?pageNumber={page_number}"
        reviews = get_reviews_from_page(driver, url)
        if not reviews:
            break
        all_reviews.extend(reviews)
        time.sleep(1)  # Be respectful and avoid hammering the server

    return all_reviews

# Example usage
asin = 'B0BY8JZ22K'  # Replace with your product's ASIN
reviews = get_all_reviews(asin)
driver.quit()

# Print reviews
for review in reviews:
    print(f"Title: {review['title']}")
    print(f"Rating: {review['rating']}")
    print(f"Content: {review['content']}")
    print(f"Date: {review['date']}")
    print(f"Reviewer: {review['reviewer']}")
    print()
