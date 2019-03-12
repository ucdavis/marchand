# Preview all emails at http://localhost:3000/rails/mailers/user_mailer
class UserMailerPreview < ActionMailer::Preview

    # Preview this email at http://localhost:3000/rails/mailers/user_mailer/lesson_request
  def lesson_request
    lesson = Lesson.last
    customer_email = 'customer_email@gmail.com'
    LessonMailer.lesson_request(lesson, customer_email)
  end
end
