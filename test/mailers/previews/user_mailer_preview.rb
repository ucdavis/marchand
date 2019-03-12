# Preview all emails at http://localhost:3000/rails/mailers/user_mailer
class UserMailerPreview < ActionMailer::Preview

  # Preview this email at http://localhost:3000/rails/mailers/user_mailer/lesson_request
  def lesson_request
    lesson = Lesson.last
    customer_email = 'customer_email@gmail.com'
    UserMailer.lesson_request(lesson, customer_email)
  end

  # Preview this email at http://localhost:3000/rails/mailers/user_mailer/image_request
  def image_request
    image = Image.first
    customer_email = 'customer_email@gmail.com'
    UserMailer.image_request(image, customer_email)
  end
end
